<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\Discovery\ClassFileToDefinitions;

interface ClassFileToDefinitionsInterface {

  /**
   * @param string $class
   * @param string $file
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  public function classFileGetDefinitions(string $class, string $file): array;

}
