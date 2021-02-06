<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionsById;

interface DefinitionsByIdInterface {

  /**
   * @return array[]
   *   Array of all definitions for this plugin type.
   */
  public function getDefinitionsById(): array;

}
