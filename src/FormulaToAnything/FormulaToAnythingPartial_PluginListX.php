<?php
declare(strict_types=1);

namespace Drupal\cu\FormulaToAnything;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartial_FormulaReplacerBase;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;

/**
 * @STA
 */
class FormulaToAnythingPartial_PluginListX extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct(Formula_PluginListInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface {
    /** @var \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface $formula */
    return (new Formula_Drilldown(
      new Formula_Select_FromPlugins($formula->getPlugins()),
      new IdToFormula_FromPlugins($formula->getPlugins()),
      $formula->allowsNull()))
      # ->withKeys('plugin', NULL)
    ;
  }

}
