<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\ObCK\Plugin\Map\PluginMapInterface;

/**
 * @_STA
 */
class FormulaToAnythingPartial_IfacePluginMap extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(PluginMapInterface $pluginMap) {
    $this->pluginMap = $pluginMap;
    parent::__construct(Formula_IfaceInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface {
    /** @var \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $formula */
    $plugins = $this->pluginMap->typeGetPlugins($formula->getInterface());
    $ff = new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull());
    return $ff;
  }

}

