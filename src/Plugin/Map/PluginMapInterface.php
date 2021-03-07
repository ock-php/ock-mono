<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin\Map;

interface PluginMapInterface {

  /**
   * @return string[]
   */
  public function getTypes(): array;

  /**
   * @param string $type
   *
   * @return \Donquixote\OCUI\Plugin\Plugin[]
   *   Format: $[$type] = $plugins.
   */
  public function typeGetPlugins(string $type): array;

}
