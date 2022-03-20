<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\Ock\Util\ReflectionUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

abstract class IncarnatorPartial_FactoryBase extends IncarnatorPartialBase {

  /**
   * @var bool
   */
  private bool $passInterface = FALSE;

  /**
   * @var bool
   */
  private bool $passIncarnator = FALSE;

  /**
   * @var array<int, mixed>
   */
  private array $moreArgs = [];

  /**
   * @param \ReflectionParameter[] $params
   *
   * @throws \Exception
   */
  protected function initParams(array $params, ParamToValueInterface $paramToValue): void {
    if (!isset($params[0])) {
      throw new \Exception('At least one parameter must exist.');
    }
    $t0 = $params[0]->getType();
    if (!$t0 instanceof \ReflectionNamedType || $t0->allowsNull() || $t0->isBuiltin()) {
      throw new \Exception('First parameter must have a class-like type hint, and not allow NULL.');
    }
    $formulaClass = $t0->getName();

    // Require first parameter type hint class to be a Formula* object.
    if ($formulaClass === FormulaInterface::class) {
      $this->setSpecifity(-1);
    }
    elseif (is_a($formulaClass, FormulaBaseInterface::class, TRUE)) {
      $reflectionFormulaClass = new \ReflectionClass($formulaClass);
      $this->setFormulaType($formulaClass);
      $this->setSpecifity(\count($reflectionFormulaClass->getInterfaceNames()));
    }
    else {
      throw new \Exception(\vsprintf('First parameter type hint of %s must be a subtype of FormulaInterface.', [
        MessageUtil::formatReflectionFunction($params[0]->getDeclaringFunction()),
      ]));
    }
    unset($params[0]);

    if (!$params) {
      return;
    }

    if ($params[1]->getName() === 'interface'
      && ($t1 = $params[1]->getType())
      && $t1->getName() === 'string'
    ) {
      if ($t1->allowsNull()) {
        throw new \Exception(vsprintf('Second parameter of %s, if string $interface, must not allow null.', [
          MessageUtil::formatReflectionFunction($params[0]->getDeclaringFunction()),
        ]));
      }
      $this->passInterface = TRUE;
      unset($params[1]);
      $i = 2;
    }
    else {
      $i = 1;
    }

    // Look at next parameter, if exists.
    if (isset($params[$i])
      && ($t1 = $params[$i]->getType())
      && $t1->getName() === IncarnatorInterface::class
      && !$t1->allowsNull()
    ) {
      $this->passIncarnator = TRUE;
      unset($params[$i]);
    }
    else {
      $this->passIncarnator = FALSE;
    }

    $this->moreArgs = ReflectionUtil::paramsDemandValues(
      $params,
      $paramToValue);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaDoGetObject(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object {
    $args = [$formula];
    if ($this->passInterface) {
      $args[] = $interface;
    }
    if ($this->passIncarnator) {
      $args[] = $incarnator;
    }
    if ($this->moreArgs) {
      $args = [...$args, ...$this->moreArgs];
    }
    return $this->invokeArgs($args);
  }

  /**
   * @param array $arguments
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  abstract protected function invokeArgs(array $arguments): ?object;

}
