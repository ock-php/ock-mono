<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToSchema;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class IdToSchema_Fixed implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $schemas;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $schemas
   */
  public function __construct(array $schemas) {
    $this->schemas = $schemas;
  }

  /**
   * @param string $id
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   *
   * @return \Donquixote\OCUI\IdToSchema\IdToSchema_Fixed
   */
  public function withSchema(string $id, FormulaInterface $schema): IdToSchema_Fixed {
    $clone = clone $this;
    $clone->schemas[$id] = $schema;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?FormulaInterface {

    return $this->schemas[$id] ?? null;
  }
}
