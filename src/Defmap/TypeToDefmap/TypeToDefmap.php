<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToDefmap;

use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMap_Buffer;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Defmap\DefinitionsById\DefinitionsById_FromType;
use Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface;

class TypeToDefmap implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface
   */
  private $typeToDefinitionsbyid;

  /**
   * @param \Donquixote\Cf\Defmap\TypeToDefinitionsbyid\TypeToDefinitionsbyidInterface $typeToDefinitionsbyid
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
