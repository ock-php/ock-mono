<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Exception\PluginListException;
use Donquixote\Ock\Formula\DecoKey\Formula_DecoKey;
use Donquixote\Ock\Formula\DecoShift\Formula_DecoShift;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Iface\Formula_IfaceInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\Formula\Select\Formula_Select_InlineExpanded;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_InlineExpanded;
use Donquixote\Ock\IdToFormula\IdToFormula_Replace;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;

# #[OckIncarnator]
class IncarnatorPartial_Iface extends IncarnatorPartial_FormulaReplacerBase {

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $pluginMap;

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private array $groupLabels;

  /**
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface $groupLabels
   *
   * @return self
   */
  #[OckIncarnator]
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
  protected function formulaGetReplacement(FormulaInterface $formula, IncarnatorInterface $incarnator): ?FormulaInterface {
    /** @var \Donquixote\Ock\Formula\Iface\Formula_IfaceInterface $formula */
    try {
      return $this->typeGetFormula(
        $formula->getInterface(),
        $formula->allowsNull(),
        $incarnator);
    }
    catch (PluginListException $e) {
      throw new IncarnatorException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @param string $type
   * @param bool $or_null
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\Ock\Exception\PluginListException
   */
  public function typeGetFormula(string $type, bool $or_null, IncarnatorInterface $incarnator): FormulaInterface {
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

    $idToFormula = new IdToFormula_Replace($idToFormula, $incarnator);

    // Support "inline" plugins.
    $idFormula = new Formula_Select_InlineExpanded(
      $idFormula,
      new IdToFormula_FromPlugins($inline_plugins),
      $incarnator);
    $idToFormula = new IdToFormula_InlineExpanded(
      $idToFormula,
      $incarnator);

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
   *
   * @throws \Donquixote\Ock\Exception\PluginListException
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
