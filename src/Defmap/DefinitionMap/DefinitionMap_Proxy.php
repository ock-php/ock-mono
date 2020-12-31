<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionMap;

use Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface;

class DefinitionMap_Proxy implements DefinitionMapInterface {

  /**
   * @var \Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var string
   */
  private $type;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface|null
   */
  private $defmap;

  /**
   * @param \Donquixote\Cf\Defmap\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param string $type
   */
  public function __construct(TypeToDefmapInterface $typeToDefmap, string $type) {
    $this->typeToDefmap = $typeToDefmap;
    $this->type = $type;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetDefinition($id): ?array {
    return $this->getDefmap()->idGetDefinition($id);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsById(): array {
    return $this->getDefmap()->getDefinitionsById();
  }

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private function getDefmap(): DefinitionMapInterface {
    return $this->defmap
      ?? $this->defmap = $this->typeToDefmap->typeGetDefmap($this->type);
  }
}
