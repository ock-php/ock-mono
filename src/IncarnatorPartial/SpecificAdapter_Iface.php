<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\Exception\AdapterException;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
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
use Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface;
use Donquixote\Ock\Plugin\Map\PluginMapInterface;

class SpecificAdapter_Iface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $pluginMap
   * @param \Donquixote\Ock\Plugin\GroupLabels\PluginGroupLabelsInterface $groupLabels
   */
  public function __construct(
    #[GetService] private PluginMapInterface $pluginMap,
    #[GetService] private PluginGroupLabelsInterface $groupLabels,
  ) {}

  /**
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public function adapt(
    #[Adaptee] Formula_IfaceInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?FormulaInterface {
    try {
      return $this->typeGetFormula(
        $formula->getInterface(),
        $formula->allowsNull(),
        $universalAdapter);
    }
    catch (PluginListException $e) {
      throw new AdapterException($e->getMessage(), 0, $e);
    }
  }

  /**
   * @throws \Donquixote\Ock\Exception\PluginListException
   */
  protected function typeGetFormula(
    string $type,
    bool $or_null,
    UniversalAdapterInterface $universalAdapter,
  ): FormulaInterface {
    $plugins = $this->pluginMap->typeGetPlugins($type);
    $inline_plugins = [];
    foreach ($plugins as $id => $plugin) {
      if ($plugin->is('inline')) {
        $inline_plugins[$id] = $plugin;
      }
    }

    // Regular plugins.
    $idFormula = new Formula_Select_FromPlugins(
      $plugins,
      $this->groupLabels->getLabels(),
    );
    $idToFormula = new IdToFormula_FromPlugins($plugins);

    $idToFormula = new IdToFormula_Replace(
      $idToFormula,
      $universalAdapter,
    );

    // Support "inline" plugins.
    $idFormula = new Formula_Select_InlineExpanded(
      $idFormula,
      new IdToFormula_FromPlugins($inline_plugins),
      $universalAdapter,
    );
    $idToFormula = new IdToFormula_InlineExpanded(
      $idToFormula,
      $universalAdapter,
    );

    // Build the drilldown formula.
    $drilldown = new Formula_Drilldown(
      $idFormula,
      $idToFormula,
      $or_null);

    // Support decorator plugins.
    return new Formula_DecoKey(
      $drilldown,
      $this->buildDecoratorDrilldown($type),
      'decorators',
    );
  }

  /**
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
      new Formula_Select_FromPlugins(
        $decorator_plugins,
        $this->groupLabels->getLabels(),
      ),
      new IdToFormula_FromPlugins($decorator_plugins),
      FALSE);

    return $drilldown;
  }

}
