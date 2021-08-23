<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;
use Donquixote\OCUI\Formula\PluginList\Formula_PluginList;
use Donquixote\OCUI\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\OCUI\Plugin\Map\PluginMapInterface;

/**
 * @STA
 */
class FormulaToAnythingPartial_IfacePluginMap extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(PluginMapInterface $pluginMap) {
    $this->pluginMap = $pluginMap;
    parent::__construct(Formula_IfaceInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, FormulaToAnythingInterface $helper): ?FormulaInterface {
    /** @var \Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface $formula */
    $plugins = $this->pluginMap->typeGetPlugins($formula->getInterface());
    $ff = new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull());
    $ff = $ff->withKeys('plugin', NULL);
    return $ff;
  }

}

