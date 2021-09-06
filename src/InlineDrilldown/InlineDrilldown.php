<?php
declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\UtilBase;

final class InlineDrilldown extends UtilBase {

  /**
   * Materializes an InlineDrilldown object from a formula.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface
   *   Materialized PluginList.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a PluginList for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    NurseryInterface $formulaToAnything
  ): InlineDrilldownInterface {

    $candidate = $formulaToAnything->breed(
      $formula,
      InlineDrilldownInterface::class);

    /** @var \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface $candidate */
    return $candidate;
  }

}
