<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKey;
use Donquixote\ObCK\Formula\Group\Formula_Group;
use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\ObCK\Plugin\Map\PluginMapInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_Group_Deco;

/**
 * This is a version of TypeToFormula* where $type is assumed to be an interface
 * name.
 */
class TypeToFormula_PluginMapDrilldownWithDecorators implements TypeToFormulaInterface {

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

    $drilldown = $this->pluginsBuildDrilldown($plugins, $or_null)
      ->withKeys('plugin', NULL);

    $decorator_plugins = [];
    foreach ($plugins as $id => $plugin) {
      $decorator_plugin = $this->buildDecoratorPlugin($plugin);
      if ($decorator_plugin !== NULL) {
        $decorator_plugins[$id] = $decorator_plugin;
      }
    }
    $decorator_drilldown = $this
      ->pluginsBuildDrilldown($decorator_plugins, FALSE)
      ->withKeys('plugin', NULL);
    return new Formula_DecoKey($drilldown, $decorator_drilldown);
  }

  /**
   * @param \Donquixote\ObCK\Plugin\Plugin $plugin
   *   Original plugin.
   *
   * @return \Donquixote\ObCK\Plugin\Plugin|null
   *   Decorator plugin based on original plugin, or NULL.
   */
  private function buildDecoratorPlugin(Plugin $plugin): ?Plugin {
    $decorator_formula = $this->buildDecoratorFormula(
      $plugin->getFormula());
    if (!$decorator_formula) {
      return NULL;
    }
    return new Plugin(
      $plugin->getLabel(),
      $plugin->getDescription(),
      $decorator_formula);
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Original formula.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   *   Decorator formula based on original formula.
   */
  private function buildDecoratorFormula(FormulaInterface $formula): ?FormulaInterface {
    if ($formula instanceof Formula_GroupValInterface) {
      $group_v2v = $formula->getV2V();
      $group_formula = $formula->getDecorated();
    }
    elseif ($formula instanceof Formula_GroupInterface) {
      $group_v2v = NULL;
      $group_formula = $formula;
    }
    else {
      return NULL;
    }
    $item_formulas = $group_formula->getItemFormulas();
    $item_labels = $group_formula->getLabels();
    reset ($item_formulas);
    if (key($item_formulas) !== 'decorated') {
      return NULL;
    }
    unset($item_formulas['decorated']);
    unset($item_labels['decorated']);
    return new Formula_GroupVal(
      new Formula_Group($item_formulas, $item_labels),
      V2V_Group_Deco::create($group_v2v));
  }

  /**
   * @param \Donquixote\ObCK\Plugin\Plugin[] $plugins
   * @param bool $or_null
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown
   */
  private function pluginsBuildDrilldown(array $plugins, bool $or_null): Formula_Drilldown {
    return new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $or_null);
  }

}
