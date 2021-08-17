<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown;
use Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\OCUI\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\OCUI\IdToFormula\IdToFormula_FromPlugins;

/**
 * @STA
 */
class FormulaToAnythingPartial_PluginList extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct(Formula_PluginListInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula): ?FormulaInterface {
    /** @var \Donquixote\OCUI\Formula\PluginList\Formula_PluginListInterface $formula */
    return (new Formula_Drilldown(
      new Formula_Select_FromPlugins($formula->getPlugins()),
      new IdToFormula_FromPlugins($formula->getPlugins()),
      $formula->allowsNull()))
      ->withKeys('plugin', NULL);
  }

}
