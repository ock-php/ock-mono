<?php
declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\UtilBase;

final class InlineDrilldown extends UtilBase {

  /**
   * Materializes an InlineDrilldown object from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface
   *   Materialized PluginList.
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   *   Cannot build a PluginList for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    NurseryInterface $formulaToAnything
  ): InlineDrilldownInterface {

    $candidate = $formulaToAnything->breed(
      $formula,
      InlineDrilldownInterface::class);

    /** @var \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface $candidate */
    return $candidate;
  }

}
