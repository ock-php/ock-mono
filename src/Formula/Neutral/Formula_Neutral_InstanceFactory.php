<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Neutral;

use Donquixote\Adaptism\Util\AttributesUtil;
use Donquixote\Adaptism\Util\ReflectionTypeUtil;
use Donquixote\Ock\Contract\FormulaHavingInterface;
use Donquixote\Ock\Contract\LabelHavingInterface;
use Donquixote\Ock\Contract\NameHavingInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Group\GroupFormulaBuilder;
use Donquixote\Ock\Formula\Iface\Formula_Iface;
use Donquixote\Ock\Util\IdentifierLabelUtil;
use Donquixote\Ock\Util\ReflectionUtil;

class Formula_Neutral_InstanceFactory extends Formula_Neutral_ProxyBase {

  /**
   * Constructor.
   *
   * @param callable(mixed...): object $factory
   */
  public function __construct(
    private readonly mixed $factory,
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function doGetDecorated(): FormulaInterface {
    $reflector = ReflectionUtil::reflectCallable($this->factory);
    $parameters = $reflector->getParameters();
    return $this->buildGroupFormula($parameters)
      ->call($this->factory);
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
      $name = AttributesUtil::getSingle($parameter, NameHavingInterface::class)
        ?->getName()
        ?? $parameter->getName();
      $label = AttributesUtil::getSingle(
        $parameter,
        LabelHavingInterface::class,
      )?->getLabel()
        ?? IdentifierLabelUtil::fromInterface($name);
      $formula = AttributesUtil::getSingle(
        $parameter,
        FormulaHavingInterface::class,
      )?->getFormula()
        ?? new Formula_Iface(
          ReflectionTypeUtil::requireGetClassLikeType($parameter),
          $parameter->allowsNull(),
        );
      $builder->add($name, $label, $formula);
    }
    return $builder;
  }

}
