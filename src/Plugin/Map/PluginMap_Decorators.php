<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Map;

use Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface;

class PluginMap_Decorators implements PluginMapInterface {

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $decorated
   */
  public function __construct(PluginMapInterface $decorated) {
    $this->decorated = $decorated;
  }

  public function getTypes(): array {
    return $this->decorated->getTypes();
  }

  public function typeGetPlugins(string $type): array {
    $plugins = [];
    foreach ($this->decorated->typeGetPlugins($type) as $id => $plugin) {
      $formula = $plugin->getFormula();
    }
    return $plugins;
  }

}
