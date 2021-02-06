<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToDefmap;

use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\Defmap\DefinitionsById\DefinitionsById_FromType;
use Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
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
