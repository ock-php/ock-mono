<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionsByTypeAndId;

interface DefinitionsByTypeAndIdInterface {

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId(): array;

}
