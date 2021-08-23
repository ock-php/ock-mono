<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown;
use Donquixote\OCUI\Formula\PluginList\Formula_PluginList;
use Donquixote\OCUI\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\OCUI\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\OCUI\Plugin\Map\PluginMapInterface;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_PluginMapDecorators implements TypeToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface
   */
  private $pluginMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $pluginMap
   */
  public function __construct(PluginMapInterface $pluginMap, TypeToFormulaInterface $decorated) {
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
      $or_null))
      ->withKeys('plugin', NULL);
  }

}
