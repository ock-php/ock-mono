<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToDefmap;

use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;

class TypeToDefmap_Buffer implements TypeToDefmapInterface {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface[]
   */
  private $definitionMaps = [];

  /**
   * @param \Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface $decorated
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
