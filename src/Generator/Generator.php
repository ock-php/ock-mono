<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Generator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\MessageUtil;
use Donquixote\ObCK\Util\UtilBase;

final class Generator extends UtilBase {

  /**
   * Materializes a generator from an interface.
   *
   * @param string $interface
   *   Interface name.
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\Generator\GeneratorInterface|null
   *   Generator. Evaluating the code of this generator should create an
   *   instance of $interface.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a generator for the given interface.
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
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\Generator\GeneratorInterface
   *   Materialized generator.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a generator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): GeneratorInterface {

    $candidate = $formulaToAnything->formula(
      $formula,
      GeneratorInterface::class);

    if ($candidate instanceof GeneratorInterface) {
      return $candidate;
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      [
        '@formula_class' => get_class($formula),
        '@interface' => GeneratorInterface::class,
        '@found' => MessageUtil::formatValue($candidate),
      ]));
  }

}
