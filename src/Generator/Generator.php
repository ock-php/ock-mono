<?php

declare(strict_types = 1);

namespace Donquixote\DID\Generator;

use Donquixote\DID\Util\MessageUtil;
use Donquixote\DID\Util\PhpUtil;
use Donquixote\DID\ValueDefinition\ValueDefinition_Call;
use Donquixote\DID\ValueDefinition\ValueDefinition_CallObjectMethod;
use Donquixote\DID\ValueDefinition\ValueDefinition_ClassName;
use Donquixote\DID\ValueDefinition\ValueDefinition_Construct;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetArgument;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetContainer;
use Donquixote\DID\ValueDefinition\ValueDefinition_GetService;
use Donquixote\DID\ValueDefinition\ValueDefinition_Parametric;
use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

class Generator implements GeneratorInterface {

  /**
   * Constructor.
   *
   * @param string $containerPlaceholder
   */
  public function __construct(
    private readonly string $containerPlaceholder = '$container',
  ) {}

  /**
   * {@inheritdoc}
   * @param bool $enclose
   */
  public function generate(mixed $definition, bool $enclose = FALSE): string {
    if (is_array($definition)) {
      return PhpUtil::phpArray(
        $this->generateMultiple($definition),
      );
    }

    if (!$definition instanceof ValueDefinitionInterface) {
      if (is_scalar($definition)) {
        return var_export($definition, TRUE);
      }
      if (is_object($definition)) {
        throw new \InvalidArgumentException(sprintf(
          'Objects must implement %s. Found %s.',
          ValueDefinitionInterface::class,
          MessageUtil::formatValue($definition),
        ));
      }
      throw new \InvalidArgumentException(sprintf(
        'Unexpected value %s.',
        MessageUtil::formatValue($definition),
      ));
    }

    if ($definition instanceof ValueDefinition_GetService) {
      return $this->containerPlaceholder
        . '->get(' . var_export($definition->id, TRUE) . ')';
    }

    if ($definition instanceof ValueDefinition_GetContainer) {
      return $this->containerPlaceholder;
    }

    if ($definition instanceof ValueDefinition_ClassName) {
      return '\\' . $definition->class . '::class';
    }

    if ($definition instanceof ValueDefinition_Construct) {
      $argsPhp = $this->generateMultiple($definition->args);
      if (is_string($definition->class)) {
        $php = PhpUtil::phpCallFqn('new \\' . $definition->class, $argsPhp);
      }
      else {
        $classPhp = $this->generate($definition->class);
        $php = PhpUtil::phpCallFqn('new (' . $classPhp . ')', $argsPhp);
      }
      return $enclose ? "($php)" : $php;
    }

    if ($definition instanceof ValueDefinition_Call) {
      $argsPhp = $this->generateMultiple($definition->args);
      $fqn = $this->generateCallFqn($definition->callback);
      return PhpUtil::phpCallFqn($fqn, $argsPhp);
    }

    if ($definition instanceof ValueDefinition_Parametric) {
      $valuePhp = $this->generate($definition->value);
      $php = 'static fn (...$args) => ' . $valuePhp;
      return $enclose ? "($php)" : $php;
    }

    if ($definition instanceof ValueDefinition_GetArgument) {
      return '$args[' . $definition->position . ']';
    }

    if ($definition instanceof ValueDefinition_CallObjectMethod) {
      $argsPhp = $this->generateMultiple($definition->args);
      $objectPhp = $this->generate($definition->object, true);
      $methodPhp = is_string($definition->method)
        ? $definition->method
        : '{' . $this->generate($definition->method) . '}';
      return PhpUtil::phpCallFqn("$objectPhp->$methodPhp", $argsPhp);
    }

    throw new \RuntimeException(sprintf(
      'Unknown value definition type %s.',
      get_class($definition),
    ));
  }

  /**
   * @param mixed $definition
   *   Definition for a callable value.
   *
   * @return string
   */
  private function generateCallFqn(mixed $definition): string {
    if (is_string($definition)) {
      // @todo Validate string content.
      return '\\' . $definition;
    }
    if (!is_array($definition)) {
      return $this->generate($definition, true);
    }
    [$classOrObjectDefinition, $methodDefinition] = $definition;
    if (is_string($classOrObjectDefinition)) {
      $methodPhp = is_string($methodDefinition)
        ? $methodDefinition
        : '{' . $this->generate($methodDefinition) . '}';
      return "\\$classOrObjectDefinition::$methodPhp";
    }
    $classOrObjectPhp = $this->generate($classOrObjectDefinition, true);
    if (str_starts_with($classOrObjectPhp, '(new ')) {
      $methodPhp = is_string($methodDefinition)
        ? $methodDefinition
        : '{' . $this->generate($methodDefinition) . '}';
      return "$classOrObjectPhp->$methodPhp";
    }
    // Use generic callable syntax, because it could be static or not.
    return PhpUtil::phpArray([
      $this->generate($classOrObjectDefinition),
      $this->generate($methodDefinition),
    ]);
  }

  /**
   * @param array $definitions
   *
   * @return array
   */
  private function generateMultiple(array $definitions): array {
    return array_map([$this, 'generate'], $definitions);
  }

}
