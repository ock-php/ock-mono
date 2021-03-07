<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Generator extends UtilBase {

  /**
   * Materializes a generator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   *   Generator. Evaluating the code of this generator should create an
   *   instance of $interface.
   */
  public static function fromIface(
    string $interface,
    FormulaToAnythingInterface $formulaToAnything
  ): ?GeneratorInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $formulaToAnything);
  }

  /**
   * Materializes a generator from a formula.
   *
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface|null
   *   Materialized generator.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?GeneratorInterface {

    $candidate = $formulaToAnything->formula(
      $formula,
      GeneratorInterface::class);

    if ($candidate instanceof GeneratorInterface) {
      return $candidate;
    }

    if (null === $candidate) {
      return null;
    }

    throw new \RuntimeException("Expected a GeneratorInterface object or NULL.");
  }

}
