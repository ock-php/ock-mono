<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToDefinitionsbyid;

use Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;

class TypeToDefinitionsbyid implements TypeToDefinitionsbyidInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @var array[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
   */
  public function __construct(DefinitionsByTypeAndIdInterface $definitionsByTypeAndId) {
    $this->definitionsByTypeAndId = $definitionsByTypeAndId;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetDefinitionsbyid(string $type): array {
    if (NULL === $this->buffer) {
      $this->buffer = $this->definitionsByTypeAndId->getDefinitionsByTypeAndId();
    }
    return $this->buffer[$type] ?? [];
  }
}
