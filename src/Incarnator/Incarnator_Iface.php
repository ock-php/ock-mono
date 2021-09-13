<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKey;
use Donquixote\Ock\Formula\DecoShift\Formula_DecoShift;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Iface\Formula_IfaceInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\Ock\IdToFormula\IdToFormula_Replace;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;

/**
 * @STA
 */
class Incarnator_Iface extends Incarnator_FormulaReplacerBase {

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $pluginMap;

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private array $groupLabels;

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface $groupLabels
   *
   * @return static
   */
  public static function create(PluginMapInterface $pluginMap, PluginGroupLabelsInterface $groupLabels): self {
    return new self($pluginMap, $groupLabels->getLabels());
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\Ock\Text\TextInterface[] $groupLabels
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
    /** @var \Donquixote\Ock\Formula\Iface\Formula_IfaceInterface $formula */
    return $this->typeGetFormula(
      $formula->getInterface(),
      $formula->allowsNull(),
      $nursery);
  }

  /**
   * @param string $type
   * @param bool $or_null
   * @param \Donquixote\Ock\Nursery\NurseryInterface $helper
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
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
