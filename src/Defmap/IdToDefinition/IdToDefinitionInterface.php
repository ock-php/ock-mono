<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\IdToDefinition;

interface IdToDefinitionInterface {

  /**
   * @param string|int $id
   *
   * @return array|null
   */
  public function idGetDefinition($id): ?array;

}
