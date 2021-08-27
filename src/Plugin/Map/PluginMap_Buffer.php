<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Map;

class PluginMap_Buffer implements PluginMapInterface {

  /**
   * @var string[]|null
   */
  private $types;

  /**
   * @var \Donquixote\ObCK\Plugin\Plugin[][]
   */
  private $pluginss = [];

  /**
   * @var \Donquixote\ObCK\Plugin\Map\PluginMapInterface
   */
  private $decorated;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Plugin\Map\PluginMapInterface $decorated
   */
  public function __construct(PluginMapInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function getTypes(): array {
    return $this->types
      ?? ($this->types = $this->decorated->getTypes());
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetPlugins(string $type): array {
    return $this->pluginss[$type]
      ?? ($this->pluginss[$type] = $this->decorated->typeGetPlugins($type));
  }

}
