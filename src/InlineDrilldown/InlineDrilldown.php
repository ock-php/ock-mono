<?php
declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class InlineDrilldown extends UtilBase {

  /**
   * Materializes an InlineDrilldown object from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface
   *   Materialized PluginList.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Cannot build a PluginList for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): InlineDrilldownInterface {

    $candidate = $incarnator->incarnate(
      $formula,
      InlineDrilldownInterface::class);

    /** @var \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface $candidate */
    return $candidate;
  }

}
