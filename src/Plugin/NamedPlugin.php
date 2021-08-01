<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin;

class NamedPlugin {

  private string $id;

  private Plugin $plugin;

  /**
   * Constructor.
   *
   * @param string $id
   *   Plugin id.
   * @param \Donquixote\OCUI\Plugin\Plugin $plugin
   *   The actual plugin object.
   */
  public function __construct(string $id, Plugin $plugin) {
    $this->id = $id;
    $this->plugin = $plugin;
  }

  /**
   * @return string
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * @return \Donquixote\OCUI\Plugin\Plugin
   */
  public function getPlugin(): Plugin {
    return $this->plugin;
  }

}
