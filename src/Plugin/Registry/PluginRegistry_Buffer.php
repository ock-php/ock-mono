<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

/**
 * Buffers plugins to prevent repeated discovery.
 */
class PluginRegistry_Buffer implements PluginRegistryInterface {

  /**
   * @var \Donquixote\Ock\Plugin\Plugin[][]|null
   */
  private ?array $pluginss;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface $decorated
   */
  public function __construct(
    private readonly PluginRegistryInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    return $this->pluginss
      ??= $this->decorated->getPluginss();
  }

}
