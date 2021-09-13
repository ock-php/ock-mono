<?php

declare(strict_types=1);

namespace Donquixote\Ock\Plugin\Map;

interface PluginMapInterface {

  /**
   * @return string[]
   */
  public function getTypes(): array;

  /**
   * @param string $type
   *
   * @return \Donquixote\Ock\Plugin\Plugin[]
   *   Format: $[$type] = $plugins.
   */
  public function typeGetPlugins(string $type): array;

}
