<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\PluginList;

/**
 * Default implementation.
 */
class Formula_PluginList implements Formula_PluginListInterface {

  /**
   * @var \Donquixote\OCUI\Plugin\Plugin[]
   */
  private $plugins;

  /**
   * @var bool
   */
  private $allowsNull;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Plugin\Plugin[] $plugins
   *   List of plugins by machine name.
   * @param bool $allowsNull
   *   TRUE if this is optional.
   */
  public function __construct(array $plugins, bool $allowsNull) {
    $this->plugins = $plugins;
    $this->allowsNull = $allowsNull;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugins(): array {
    return $this->plugins;
  }

  /**
   * {@inheritdoc}
   */
  public function allowsNull(): bool {
    return $this->allowsNull;
  }

}
