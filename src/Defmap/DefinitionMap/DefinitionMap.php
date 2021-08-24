<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionMap;

class DefinitionMap implements DefinitionMapInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @param array $definitions
   */
  public function __construct(array $definitions) {
    $this->definitions = $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetDefinition($id): ?array {

    return $this->definitions[$id] ?? null;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinitionsById(): array {

    return $this->definitions;
  }
}
