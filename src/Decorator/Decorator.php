<?php

declare(strict_types=1);

namespace Donquixote\Ock\Decorator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\UtilBase;

final class Decorator extends UtilBase {

  /**
   * Materializes a Decorator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Decorator\DecoratorInterface|null
   *   Decorator. Evaluating the code of this Decorator should create an
   *   instance of $interface.
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   *   Cannot build a Decorator for the given interface.
   */
  public static function fromIface(
    string $interface,
    NurseryInterface $formulaToAnything
  ): ?DecoratorInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $formulaToAnything);
  }

  /**
   * Materializes a Decorator from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Decorator\DecoratorInterface
   *   Materialized Decorator.
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   *   Cannot build a Decorator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    NurseryInterface $formulaToAnything
  ): DecoratorInterface {

    /** @var \Donquixote\Ock\Decorator\DecoratorInterface $candidate */
    $candidate = $formulaToAnything->breed(
      $formula,
      DecoratorInterface::class);

    return $candidate;
  }

}
