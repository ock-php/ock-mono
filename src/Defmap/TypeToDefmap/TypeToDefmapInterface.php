<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToDefmap;

use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;

interface TypeToDefmapInterface {

  /**
   * @param string $type
   *
   * @return \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function typeGetDefmap(string $type): DefinitionMapInterface;

}
