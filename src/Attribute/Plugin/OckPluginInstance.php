<?php

declare(strict_types=1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\Util\MessageUtil;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Group\GroupFormulaBuilder;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Plugin\PluginDeclaration;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Util\StringUtil;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class OckPluginInstance extends PluginAttributeBase {

  /**
   * {@inheritdoc}
   */
  public function onClass(\ReflectionClass $reflectionClass): PluginDeclaration {
    if ($reflectionClass->isAbstract()) {
      throw new \RuntimeException(\sprintf(
        'Class %s must not be abstract.',
        $reflectionClass->getName()));
    }
    $formula = $this->buildGroupFormula($reflectionClass->getConstructor()?->getParameters() ?? [])
      ->construct($reflectionClass->getName());
    return $this->formulaGetNamedPlugin(
      $formula,
      $reflectionClass->getInterfaceNames());
  }

  /**
   * {@inheritdoc}
   */
  public function onMethod(\ReflectionMethod $reflectionMethod): PluginDeclaration {
    if (!$reflectionMethod->isStatic()) {
      throw new MalformedDeclarationException(\sprintf(
        'Method %s must be static.',
        MessageUtil::formatReflector($reflectionMethod),
      ));
    }
    $returnClass = ReflectionTypeUtil::requireGetClassLikeType($reflectionMethod);
    try {
      $reflectionReturnClass = new \ReflectionClass($returnClass);
    }
    catch (\ReflectionException) {
      throw new MalformedDeclarationException('Undefined class ' . $returnClass);
    }
    $formula = $this->buildGroupFormula($reflectionMethod->getParameters())
      ->call([
        $reflectionMethod->getDeclaringClass()->getName(),
        $reflectionMethod->getName(),
      ]);
    return $this->formulaGetNamedPlugin(
      $formula,
      $reflectionReturnClass->getInterfaceNames(),
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return \Donquixote\Ock\Formula\Group\GroupFormulaBuilder
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  private function buildGroupFormula(array $parameters): GroupFormulaBuilder {
    $builder = Formula::group();
    foreach ($parameters as $parameter) {
      $name = $parameter->getName();
      $param_formula = self::paramGetFormula($parameter);
      if (!$param_formula) {
        throw new MalformedDeclarationException(\sprintf(
          'Cannot build formula for parameter $%s.',
          MessageUtil::formatReflector($parameter),
        ));
      }
      $param_label = Text::t(
        StringUtil::methodNameGenerateLabel($name),
      );

      $builder->add($name, $param_formula, $param_label);
    }
    return $builder;
  }

  /**
   * @param \ReflectionParameter $param
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   */
  private static function paramGetFormula(\ReflectionParameter $param): ?FormulaInterface {
    $rtype = $param->getType();
    if (!$rtype instanceof \ReflectionNamedType || $rtype->isBuiltin()) {
      return NULL;
    }

    $formula = new Formula_Iface($rtype->getName(), $param->allowsNull());

    if (!$param->isOptional()) {
      return $formula;
    }

    try {
      $default = $param->getDefaultValue();
    }
    catch (\ReflectionException $e) {
      // This should be unreachable code.
      // Convert exception to unchecked.
      throw new \RuntimeException($e->getMessage(), 0, $e);
    }

    if ($default !== NULL) {
      // Unsupported default value.
      throw new MalformedDeclarationException('Optional parameters must have default NULL.');
    }

    return $formula;
  }

}
