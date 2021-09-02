<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Decorator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Decorator extends UtilBase {

  /**
   * Materializes a Decorator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\Decorator\DecoratorInterface|null
   *   Decorator. Evaluating the code of this Decorator should create an
   *   instance of $interface.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
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
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\Decorator\DecoratorInterface
   *   Materialized Decorator.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a Decorator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): DecoratorInterface {

    /** @var \Donquixote\ObCK\Decorator\DecoratorInterface $candidate */
    $candidate = $formulaToAnything->formula(
      $formula,
      DecoratorInterface::class);

    return $candidate;
  }

}
