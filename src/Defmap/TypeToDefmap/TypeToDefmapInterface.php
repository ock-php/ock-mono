<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToDefmap;

use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;

interface TypeToDefmapInterface {

  /**
   * @param string $type
   *
   * @return \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap(string $type): DefinitionMapInterface;

}
