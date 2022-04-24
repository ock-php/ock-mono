<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlinePluginList;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Util\UtilBase;

final class InlinePluginList extends UtilBase {

  /**
   * Materializes a PluginList from a formula.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula.
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *   Service that can materialize other objects from formulas.
   *
   * @return \Donquixote\Ock\InlinePluginList\InlinePluginListInterface
   *   Materialized PluginList.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   *   Cannot build a PluginList for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    UniversalAdapterInterface $universalAdapter
  ): InlinePluginListInterface {
    return FormulaAdapter::requireObject(
      $formula,
      InlinePluginListInterface::class,
      $universalAdapter,
    );
  }

}
