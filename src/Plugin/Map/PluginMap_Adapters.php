<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Map;

class PluginMap_Adapters implements PluginMapInterface {

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $decorated;

  /**
   * @var \Donquixote\Ock\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $adaptersMap;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $decorated
   * @param \Donquixote\Ock\Plugin\Map\PluginMapInterface $adaptersMap
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
