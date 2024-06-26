<?php

declare(strict_types=1);

namespace Ock\Ock\Plugin\Registry;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

/**
 * Buffers plugins to prevent repeated discovery.
 */
#[AsDecorator(PluginRegistryInterface::class)]
class PluginRegistry_Buffer implements PluginRegistryInterface {

  /**
   * @var \Ock\Ock\Plugin\Plugin[][]|null
   */
  private ?array $pluginss;

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Plugin\Registry\PluginRegistryInterface $decorated
   */
  public function __construct(
    private readonly PluginRegistryInterface $decorated,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getPluginsByType(): array {
    return $this->pluginss
      ??= $this->decorated->getPluginsByType();
  }

}
