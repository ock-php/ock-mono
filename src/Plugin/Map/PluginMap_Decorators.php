<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin\Map;

use Donquixote\OCUI\Plugin\Registry\PluginRegistryInterface;

class PluginMap_Decorators implements PluginMapInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Map\PluginMapInterface
   */
  private PluginMapInterface $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Map\PluginMapInterface $decorated
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
