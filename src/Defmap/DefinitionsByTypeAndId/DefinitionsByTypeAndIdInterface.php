<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionsByTypeAndId;

interface DefinitionsByTypeAndIdInterface {

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId(): array;

}
