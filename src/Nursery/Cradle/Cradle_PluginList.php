<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Nursery\Cradle;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;

/**
 * @STA
 */
class Cradle_PluginList extends Cradle_FormulaReplacerBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct(Formula_PluginListInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, NurseryInterface $helper): ?FormulaInterface {
    /** @var \Donquixote\ObCK\Formula\PluginList\Formula_PluginListInterface $formula */
    $plugins = $formula->getPlugins();
    $ff = (new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull()));

    return $ff;
  }

}
