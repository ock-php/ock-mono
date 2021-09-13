<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Registry;

interface PluginRegistryInterface {

  /**
   *
   * @return \Donquixote\Ock\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public function getPluginss(): array;

}
