<?php
declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class InlinePluginList extends UtilBase {

  /**
   * Materializes a PluginList from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $formulaToAnything
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\InlinePluginList\InlinePluginListInterface
   *   Materialized PluginList.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Cannot build a PluginList for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $formulaToAnything
  ): InlinePluginListInterface {

    /** @var \Donquixote\Ock\InlinePluginList\InlinePluginListInterface $object */
    $object = $formulaToAnything->incarnate(
      $formula,
      InlinePluginListInterface::class);

    return $object;
  }

}
