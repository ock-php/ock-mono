<?php
declare(strict_types=1);

namespace Donquixote\Cf\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class IdToSchema_Fixed implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  private $schemas;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface[] $schemas
   */
  public function __construct(array $schemas) {
    $this->schemas = $schemas;
  }

  /**
   * @param string $id
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\IdToSchema\IdToSchema_Fixed
   */
  public function withSchema(string $id, CfSchemaInterface $schema): IdToSchema_Fixed {
    $clone = clone $this;
    $clone->schemas[$id] = $schema;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {

    return $this->schemas[$id] ?? null;
  }
}
