<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToDefmap;

use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;

class TypeToDefmap_Buffer implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface[]
   */
  private $definitionMaps = [];

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface $decorated
   */
  public function __construct(TypeToDefmapInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function typeGetDefmap(string $type): DefinitionMapInterface {
    return array_key_exists($type, $this->definitionMaps)
      ? $this->definitionMaps[$type]
      : $this->definitionMaps[$type] = $this->decorated->typeGetDefmap($type);
  }
}
