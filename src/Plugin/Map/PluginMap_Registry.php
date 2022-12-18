<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Map;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Adaptism\Attribute\Service;
use Donquixote\Ock\Plugin\Registry\PluginRegistryInterface;

#[Service]
class PluginMap_Registry implements PluginMapInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface $registry
   */
  public function __construct(
    #[GetService]
    private readonly PluginRegistryInterface $registry,
  ) {}

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
