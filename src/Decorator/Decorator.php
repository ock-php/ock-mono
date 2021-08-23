<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Decorator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\MessageUtil;
use Donquixote\OCUI\Util\UtilBase;

final class Decorator extends UtilBase {

  /**
   * Materializes a Decorator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\OCUI\Decorator\DecoratorInterface|null
   *   Decorator. Evaluating the code of this Decorator should create an
   *   instance of $interface.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   *   Cannot build a Decorator for the given interface.
   */
  public static function fromIface(
    string $interface,
    FormulaToAnythingInterface $formulaToAnything
  ): ?DecoratorInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $formulaToAnything);
  }

  /**
   * Materializes a Decorator from a formula.
   *
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\OCUI\Decorator\DecoratorInterface
   *   Materialized Decorator.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   *   Cannot build a Decorator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): DecoratorInterface {

    $candidate = $formulaToAnything->formula(
      $formula,
      DecoratorInterface::class);

    if ($candidate instanceof DecoratorInterface) {
      return $candidate;
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      [
        '@formula_class' => get_class($formula),
        '@interface' => DecoratorInterface::class,
        '@found' => MessageUtil::formatValue($candidate),
      ]));
  }

}
