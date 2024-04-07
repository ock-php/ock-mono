<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

use Donquixote\DID\Attribute\Parameter\GetService;
use Donquixote\DID\Attribute\Service;

/**
 * Buffers plugins to prevent repeated discovery.
 */
#[Service]
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
    #[GetService(serviceIdSuffix: 'decorated')]
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
