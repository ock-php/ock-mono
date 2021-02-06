<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToDefinitionsbyid;

use Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;

class TypeToDefinitionsbyid implements TypeToDefinitionsbyidInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @var array[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
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
