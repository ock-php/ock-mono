<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKey;
use Donquixote\ObCK\Formula\DecoShift\Formula_DecoShift;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\ObCK\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\ObCK\IdToFormula\IdToFormula_Replace;
use Donquixote\ObCK\Plugin\Map\PluginMapInterface;

/**
 * @STA
 */
class FormulaToAnythingPartial_Iface extends FormulaToAnythingPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $pluginMap;

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
    return $this->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull(),
      $helper);
  }

  /**
   * @param string $type
   * @param bool $or_null
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function typeGetFormula(string $type, bool $or_null, FormulaToAnythingInterface $helper): FormulaInterface {
    $plugins = $this->pluginMap->typeGetPlugins($type);
    $inline_plugins = [];
    foreach ($plugins as $id => $plugin) {
      if ($plugin->is('inline')) {
        $inline_plugins[$id] = $plugin;
      }
    }

    // Regular plugins.
    $idFormula = new Formula_Select_FromPlugins($plugins);
    $idToFormula = new IdToFormula_FromPlugins($plugins);

    $idToFormula = new IdToFormula_Replace($idToFormula, $helper);

    // Support "inline" plugins.
    $idFormula = new Formula_Select_InlineExpanded(
      $idFormula,
      new IdToFormula_FromPlugins($inline_plugins));
    $idToFormula = new IdToFormula_InlineExpanded(
      $idToFormula,
      $helper);

    // Build the drilldown formula.
    $drilldown = new Formula_Drilldown(
      $idFormula,
      $idToFormula,
      $or_null);

    // Adjust keys.
    $drilldown = $drilldown->withKeys('plugin', NULL);

    // Support decorator plugins.
    return new Formula_DecoKey(
      $drilldown,
      $this->buildDecoratorDrilldown($type),
      'decorators');
  }

  /**
   * @param string $type
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private function buildDecoratorDrilldown(string $type): FormulaInterface {

    $raw_decorator_plugins = $this->pluginMap->typeGetPlugins("decorator<$type>");

    $decorator_plugins = [];
    foreach ($raw_decorator_plugins as $id => $plugin) {
      $decorator_plugins[$id] = $plugin->withFormula(
        new Formula_DecoShift(
          $plugin->getFormula()));
    }

    return $this
      ->pluginsBuildDrilldown($decorator_plugins, FALSE)
      ->withKeys('plugin', NULL);
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
