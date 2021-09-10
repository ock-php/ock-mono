<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Nursery\Cradle;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKey;
use Donquixote\ObCK\Formula\DecoShift\Formula_DecoShift;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\ObCK\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\ObCK\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\ObCK\IdToFormula\IdToFormula_Replace;
use Donquixote\ObCK\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\ObCK\Plugin\Map\PluginMapInterface;

/**
 * @STA
 */
class Cradle_Iface extends Cradle_FormulaReplacerBase {

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $pluginMap;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $groupLabels;

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\ObCK\Plugin\GroupLabels\PluginGroupLabelsInterface $groupLabels
   *
   * @return static
   */
  public static function create(PluginMapInterface $pluginMap, PluginGroupLabelsInterface $groupLabels): self {
    return new self($pluginMap, $groupLabels->getLabels());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\ObCK\Text\TextInterface[] $groupLabels
   */
  public function __construct(PluginMapInterface $pluginMap, array $groupLabels = []) {
    $this->pluginMap = $pluginMap;
    parent::__construct(Formula_IfaceInterface::class);
    $this->groupLabels = $groupLabels;
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, NurseryInterface $nursery): ?FormulaInterface {
    /** @var \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $formula */
    return $this->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull(),
      $nursery);
  }

  /**
   * @param string $type
   * @param bool $or_null
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $helper
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function typeGetFormula(string $type, bool $or_null, NurseryInterface $helper): FormulaInterface {
    $plugins = $this->pluginMap->typeGetPlugins($type);
    $inline_plugins = [];
    foreach ($plugins as $id => $plugin) {
      if ($plugin->is('inline')) {
        $inline_plugins[$id] = $plugin;
      }
    }

    // Regular plugins.
    $idFormula = new Formula_Select_FromPlugins($plugins, $this->groupLabels);
    $idToFormula = new IdToFormula_FromPlugins($plugins);

    $idToFormula = new IdToFormula_Replace($idToFormula, $helper);

    // Support "inline" plugins.
    $idFormula = new Formula_Select_InlineExpanded(
      $idFormula,
      new IdToFormula_FromPlugins($inline_plugins),
      $helper);
    $idToFormula = new IdToFormula_InlineExpanded(
      $idToFormula,
      $helper);

    // Build the drilldown formula.
    $drilldown = new Formula_Drilldown(
      $idFormula,
      $idToFormula,
      $or_null);

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

    $drilldown = new Formula_Drilldown(
      new Formula_Select_FromPlugins($decorator_plugins, $this->groupLabels),
      new IdToFormula_FromPlugins($decorator_plugins),
      FALSE);

    return $drilldown;
  }

}
