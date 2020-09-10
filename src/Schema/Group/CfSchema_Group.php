<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

class CfSchema_Group implements CfSchema_GroupInterface {

  /**
   * @var \Donquixote\Cf\Core\Schema\CfSchemaInterface[]
   */
  private $schemas;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function create(array $schemas = [], array $labels = []): CfSchema_Group {
    return new self($schemas, $labels);
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   */
  public function __construct(array $schemas, array $labels) {

    foreach ($schemas as $k => $itemSchema) {
      if (!$itemSchema instanceof CfSchemaInterface) {
        throw new \InvalidArgumentException("Item schema at key $k must be instance of CfSchemaInterface.");
      }
    }

    $this->schemas = $schemas;
    $this->labels = $labels;
  }

  /**
   * @param string $key
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param null $label
   *
   * @return \Donquixote\Cf\Schema\Group\CfSchema_Group
   */
  public function withItem($key, CfSchemaInterface $schema, $label = NULL): CfSchema_Group {
    $clone = clone $this;
    $clone->schemas[$key] = $schema;
    $clone->labels[$key] = $label ?? $key;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemSchemas(): array {
    return $this->schemas;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->labels;
  }
}
