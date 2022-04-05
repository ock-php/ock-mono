<?php

declare(strict_types=1);

namespace Donquixote\Ock\Generator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class Generator extends UtilBase {

  /**
   * Materializes a generator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface
   *   Generator. Evaluating the code of this generator should create an
   *   instance of $interface.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Cannot build a generator for the given interface.
   */
  public static function fromIface(
    string $interface,
    IncarnatorInterface $incarnator
  ): GeneratorInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $incarnator);
  }

  /**
   * Materializes a generator from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\Generator\GeneratorInterface
   *   Materialized generator.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Cannot build a generator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): GeneratorInterface {
    return Incarnator::getObject(
      $formula,
      GeneratorInterface::class,
      $incarnator);
  }

}
