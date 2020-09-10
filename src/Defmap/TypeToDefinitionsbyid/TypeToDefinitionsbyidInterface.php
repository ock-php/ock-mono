<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToDefinitionsbyid;

interface TypeToDefinitionsbyidInterface {

  /**
   * @param string $type
   *
   * @return array[]
   *   Array of all plugin definitions for the given plugin type.
   */
  public function typeGetDefinitionsbyid(string $type): array;

}
