<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionMap;


use Donquixote\Cf\Defmap\DefinitionsById\DefinitionsByIdInterface;

/**
 * Buffers the plugins for a specific
 */
class DefinitionMap_Buffer implements DefinitionMapInterface {

  /**
   * @var array[]|null
   */
  private $definitions;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionsById\DefinitionsByIdInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionsById\DefinitionsByIdInterface $decorated
   */
  public function __construct(DefinitionsByIdInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetDefinition($id): ?array {
    $definitions = $this->getDefinitionsById();
    return $definitions[$id] ?? null;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsById(): array {
    return $this->definitions
      ?? $this->definitions = $this->decorated->getDefinitionsById();
  }
}
