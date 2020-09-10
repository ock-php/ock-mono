<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionsById;

interface DefinitionsByIdInterface {

  /**
   * @return array[]
   *   Array of all definitions for this plugin type.
   */
  public function getDefinitionsById(): array;

}
