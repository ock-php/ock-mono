<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionsById;

use Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class DefinitionsById_FromType implements DefinitionsByIdInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsById;

  /**
   * @var string
   */
  private $type;

  /**
   * WickedDefinitionsByIdDiscovery constructor.
   *
   * @param \Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsById
   * @param string $type
   */
  public function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsById, string $type) {
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
