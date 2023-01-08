<?php

declare(strict_types = 1);

namespace Donquixote\DID\Generator;

use Donquixote\DID\Exception\CodegenException;
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

class ValueDefinitionToPhp implements ValueDefinitionToPhpInterface {

  /**
   * Constructor.
   *
   * @param string $containerPlaceholder
   */
  public function __construct(
    private string $containerPlaceholder = '$container',
  ) {}

  /**
   * @param string $containerPlaceholder
   *
   * @return static
   */
  public function withContainerPlaceholder(string $containerPlaceholder): static {
    $clone = clone $this;
    $clone->containerPlaceholder = $containerPlaceholder;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function generate(mixed $definition, bool $enclose = FALSE): string {
    /** @noinspection PhpVoidFunctionResultUsedInspection */
    return match (gettype($definition)) {
      'array' => PhpUtil::phpArray(
        $this->generateMultiple($definition),
      ),
      'boolean',
      'integer',
      'string',
      'double',
      'NULL' => var_export($definition, TRUE),
      'resource',
      'resource (closed)' => $this->fail('Cannot export resources'),
      'object' => match (get_class($definition)) {
        ValueDefinition_GetService::class => $this->containerPlaceholder
          . '->get(' . var_export($definition->id, TRUE) . ')',
        ValueDefinition_Call::class => PhpUtil::phpCallFqn(
          $this->generateCallFqn($definition->callback),
          $this->generateMultiple($definition->args),
        ),
        ValueDefinition_Parametric::class => PhpUtil::phpEncloseIf(
          'static fn (...$args) => ' . $this->generate($definition->value),
          $enclose,
        ),
        ValueDefinition_Construct::class => PhpUtil::phpCallFqn(
          is_string($definition->class)
            ? '\\' . $definition->class
            : $this->generate($definition->class),
          $this->generateMultiple($definition->args),
          $enclose,
        ),
        ValueDefinition_GetArgument::class => '$args[' . $definition->position . ']',
        ValueDefinition_ClassName::class => '\\' . $definition->class . '::class',
        ValueDefinition_GetContainer::class => $this->containerPlaceholder,
        ValueDefinition_CallObjectMethod::class => PhpUtil::phpCallMethod(
          $this->generate($definition->object, true),
          is_string($definition->method)
            ? $definition->method
            : '{' . $this->generate($definition->method) . '}',
          $this->generateMultiple($definition->args),
        ),
        default => $definition instanceof ValueDefinitionInterface
          ? $this->fail(sprintf(
            'Unknown value definition type %s.',
            get_class($definition),
          ))
          : $this->fail(sprintf(
            'Objects must implement %s. Found %s.',
            ValueDefinitionInterface::class,
            MessageUtil::formatValue($definition),
          )),
      },
      default => $this->fail(sprintf(
        'Unexpected value %s.',
        MessageUtil::formatValue($definition),
      )),
    };
  }

  /**
   * @param mixed $definition
   *   Definition for a callable value.
   *
   * @return string
   * @throws \Donquixote\DID\Exception\CodegenException
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

  /**
   * @throws \Donquixote\DID\Exception\CodegenException
   */
  private function fail(string $message): never {
    throw new CodegenException($message);
  }

}
