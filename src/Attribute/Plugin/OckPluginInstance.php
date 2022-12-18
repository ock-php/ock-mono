<?php

declare(strict_types=1);

namespace Donquixote\Ock\Attribute\Plugin;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Exception\MalformedDeclarationException;
use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\MessageUtil;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Ock\Contract\FormulaHavingInterface;
use Donquixote\Ock\Contract\LabelHavingInterface;
use Donquixote\Ock\Contract\NameHavingInterface;
use Donquixote\Ock\Exception\FormulaException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Group\GroupFormulaBuilder;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Plugin\PluginDeclaration;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Util\IdentifierLabelUtil;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class OckPluginInstance extends PluginAttributeBase {

  /**
   * {@inheritdoc}
   */
  public function onClass(\ReflectionClass $reflectionClass): PluginDeclaration {
    if ($reflectionClass->isAbstract()) {
      throw new \RuntimeException(\sprintf(
        'Class %s must not be abstract.',
        $reflectionClass->getName(),
      ));
    }
    $formula = $this->buildGroupFormula($reflectionClass->getConstructor()?->getParameters() ?? [])
      ->construct($reflectionClass->getName());
    return $this->formulaGetPluginDeclaration(
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
    return $this->formulaGetPluginDeclaration(
      $formula,
      $reflectionReturnClass->isInterface()
        ? [$reflectionReturnClass->getName(), ...$reflectionReturnClass->getInterfaceNames()]
        : $reflectionReturnClass->getInterfaceNames(),
    );
  }

  /**
   * @param \ReflectionParameter[] $parameters
   *
   * @return \Donquixote\Ock\Formula\Group\GroupFormulaBuilder
   *
   * @throws \Donquixote\Adaptism\Exception\MalformedDeclarationException
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  private function buildGroupFormula(array $parameters): GroupFormulaBuilder {
    $builder = Formula::group();
    foreach ($parameters as $i => $parameter) {
      if ($attribute = AttributesUtil::getSingle($parameter, GetService::class)) {
        $serviceId = $attribute->getId()
          ?? ReflectionTypeUtil::requireGetClassLikeType($parameter);
        $builder->add(
          'service.' . $i,
          Text::i($i),
          Formula::serviceExpression($serviceId),
        );
        continue;
      }
      $name = AttributesUtil::requireGetSingle($parameter, NameHavingInterface::class)
        ->getName();
      $label = AttributesUtil::getSingle($parameter, LabelHavingInterface::class)
        ?->getLabel()
        ?? IdentifierLabelUtil::fromInterface($name);
      $formula = AttributesUtil::getSingle($parameter, FormulaHavingInterface::class)
        ?->getFormula()
        ?? new Formula_Iface(
          ReflectionTypeUtil::requireGetClassLikeType($parameter),
          $parameter->allowsNull(),
        );
      try {
        $builder->add($name, $label, $formula);
      }
      catch (FormulaException $e) {
        throw new MalformedDeclarationException(sprintf(
          'Problem on %s: %s.',
          MessageUtil::formatReflector($parameter),
          $e->getMessage(),
        ), 0, $e);
      }
    }
    return $builder;
  }

}
