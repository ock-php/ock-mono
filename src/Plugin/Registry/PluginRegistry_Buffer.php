<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

class PluginRegistry_Buffer implements PluginRegistryInterface {

  /**
   * @var \Donquixote\Ock\Plugin\Plugin[][]|null
   */
  private $pluginss;

  /**
   * @var \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface
   */
  private $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Plugin\Registry\PluginRegistryInterface $decorated
   */
  public function __construct(PluginRegistryInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginss(): array {
    return $this->pluginss
      ?? ($this->pluginss = $this->decorated->getPluginss());
  }

}
