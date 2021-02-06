<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionsById;

use Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class DefinitionsById_FromType implements DefinitionsByIdInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsById;

  /**
   * @var string
   */
  private $type;

  /**
   * WickedDefinitionsByIdDiscovery constructor.
   *
   * @param \Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsById
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
