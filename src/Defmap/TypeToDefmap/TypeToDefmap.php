<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\TypeToDefmap;

use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Defmap\DefinitionsById\DefinitionsById_FromType;
use Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @param \Donquixote\ObCK\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
   */
  public function __construct(TypeToDefinitionsbyidInterface $typeToDefinitionsbyid) {
    $this->typeToDefinitionsbyid = $typeToDefinitionsbyid;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetDefmap(string $type): DefinitionMapInterface {

    $definitionsById = new DefinitionsById_FromType(
      $this->typeToDefinitionsbyid,
      $type);

    return new DefinitionMap_Buffer($definitionsById);
  }
}
