<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Map;

use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class PluginMap_Adapters implements PluginMapInterface {

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $decorated;

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $adaptersMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $decorated
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $adaptersMap
   */
  public function __construct(PluginMapInterface $decorated, PluginMapInterface $adaptersMap) {
    $this->decorated = $decorated;
    $this->adaptersMap = $adaptersMap;
  }

  /**
   * {@inheritdoc}
   */
  public function getTypes(): array {
    return $this->decorated->getTypes();
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetPlugins(string $type): array {
    $plugins = [];
    foreach ($this->decorated->typeGetPlugins($type) as $id => $plugin) {
      $formula = $plugin->getFormula();
    }
    return $plugins;
  }

}
