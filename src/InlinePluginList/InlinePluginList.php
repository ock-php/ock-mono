<?php
declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\UtilBase;

final class InlinePluginList extends UtilBase {

  /**
   * Materializes a PluginList from a formula.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface
   *   Materialized PluginList.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a PluginList for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): InlinePluginListInterface {

    /** @var \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface $object */
    $object = $formulaToAnything->formula(
      $formula,
      InlinePluginListInterface::class);

    return $object;
  }

}
