<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Registry;

interface PluginRegistryInterface {

  /**
   * @return \Donquixote\ObCK\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  public function getPluginss(): array;

}
