<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\ObCK\Plugin\Map\PluginMapInterface;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_PluginMapDrilldown implements TypeToFormulaInterface {

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
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetFormula(string $type, bool $or_null): FormulaInterface {
    $plugins = $this->pluginMap->typeGetPlugins($type);
    return (new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $or_null));
  }

}
