<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\Ock\Nursery\NurseryInterface;

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
  protected function formulaGetReplacement(FormulaInterface $formula, NurseryInterface $nursery): ?FormulaInterface {
    /** @var \Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface $formula */
    $plugins = $formula->getPlugins();
    $ff = (new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull()));

    return $ff;
  }

}
