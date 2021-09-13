<?php
declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Iface\Formula_IfaceInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;

/**
 * @_STA
 */
class Incarnator_IfacePluginMap extends Incarnator_FormulaReplacerBase {

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(PluginMapInterface $pluginMap) {
    $this->pluginMap = $pluginMap;
    parent::__construct(Formula_IfaceInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $nursery): ?FormulaInterface {
    /** @var \Donquixote\Ock\Formula\Iface\Formula_IfaceInterface $formula */
    $plugins = $this->pluginMap->typeGetPlugins($formula->getInterface());
    $ff = new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull());
    return $ff;
  }

}

