<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Plugin\Discovery;

interface ClassToPluginsInterface {

  /**
   * Finds plugins in a specific class file.
   *
   * @param string $class
   *   Class name.
   * @param string $file
   *   File that defines the class.
   *
   * @return \Donquixote\OCUI\Plugin\Plugin[][]
   *   Format: $[$type][$id] = $plugin
   *
   * @throws \ReflectionException
   *   Class cannot be loaded.
   */
  public function classGetPluginss(string $class, string $file): array;

}
