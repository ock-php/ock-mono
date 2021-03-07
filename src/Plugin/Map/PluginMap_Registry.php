<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin\Map;

use Donquixote\OCUI\Plugin\Registry\PluginRegistryInterface;

class PluginMap_Registry implements PluginMapInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Registry\PluginRegistryInterface
   */
  private $registry;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Registry\PluginRegistryInterface $registry
   */
  public function __construct(PluginRegistryInterface $registry) {
    $this->registry = $registry;
  }

  /**
   * {@inheritdoc}
   */
  public function getTypes(): array {
    $pluginss = $this->registry->getPluginss();
    return array_keys($pluginss);
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetPlugins(string $type): array {
    $pluginss = $this->registry->getPluginss();
    return $pluginss[$type] ?? [];
  }

}
