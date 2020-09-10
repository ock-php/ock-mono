<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToDefinitionsbyid;

use Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;

class TypeToDefinitionsbyid implements TypeToDefinitionsbyidInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @var array[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
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
