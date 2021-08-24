<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToDefmap;

use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;

interface TypeToDefmapInterface {

  /**
   * @param string $type
   *
   * @return \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap(string $type): DefinitionMapInterface;

}
