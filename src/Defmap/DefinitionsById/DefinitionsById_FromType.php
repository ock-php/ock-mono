<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionsById;

use Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class DefinitionsById_FromType implements DefinitionsByIdInterface {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsById;

  /**
   * @var string
   */
  private $type;

  /**
   * WickedDefinitionsByIdDiscovery constructor.
   *
   * @param \Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsById
   * @param string $type
   */
  public function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsById, $type) {
    $this->typeToDefinitionsById = $typeToDefinitionsById;
    $this->type = $type;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsById(): array {
    return $this->typeToDefinitionsById->typeGetDefinitionsbyid($this->type);
  }

}
