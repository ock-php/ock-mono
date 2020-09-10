<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionsByTypeAndId;

class DefinitionsByTypeAndId_Empty implements DefinitionsByTypeAndIdInterface {

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsByTypeAndId(): array {
    return [];
  }
}
