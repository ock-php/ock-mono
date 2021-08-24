<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Registry;

class PluginRegistry_Multiple implements PluginRegistryInterface {

  /**
   * @var \Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface[]
   */
  private $registries;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Registry\PluginRegistryInterface[] $registries
   */
  public function __construct(array $registries) {
    $this->registries = $registries;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    $pluginss = [];
    foreach ($this->registries as $registry) {
      foreach ($registry->getPluginss() as $type => $plugins) {
        foreach ($plugins as $id => $plugin) {
          $pluginss[$type][$id] = $plugin;
        }
      }
    }
    return $pluginss;
  }

}
