<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionsByTypeAndId;

interface DefinitionsByTypeAndIdInterface {

  /**
   * @return array[][]
   *   Format: $[$type][$id] = $definition
   */
  public function getDefinitionsByTypeAndId(): array;

}
