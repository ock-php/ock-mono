<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\Map;

interface PluginMapInterface {

  /**
   * @return string[]
   */
  public function getTypes(): array;

  /**
   * @param string $type
   *
   * @return \Donquixote\ObCK\Plugin\Plugin[]
   *   Format: $[$type] = $plugins.
   */
  public function typeGetPlugins(string $type): array;

}
