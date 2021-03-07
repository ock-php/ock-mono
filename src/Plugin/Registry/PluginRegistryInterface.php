<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin\Registry;

interface PluginRegistryInterface {

  /**
   * @return \Donquixote\OCUI\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   */
  public function getPluginss(): array;

}
