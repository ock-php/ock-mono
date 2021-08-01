<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Generator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Formula;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\MessageUtil;
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
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\OCUI\Generator\GeneratorInterface
   *   Materialized generator.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
