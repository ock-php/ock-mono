<?php
declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\UtilBase;

final class InlinePluginList extends UtilBase {

  /**
   * Materializes a PluginList from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
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
    NurseryInterface $formulaToAnything
  ): InlinePluginListInterface {

    /** @var \Donquixote\Ock\InlinePluginList\InlinePluginListInterface $object */
    $object = $formulaToAnything->breed(
      $formula,
      InlinePluginListInterface::class);

    return $object;
  }

}
