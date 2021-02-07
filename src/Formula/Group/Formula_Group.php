<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Group implements Formula_GroupInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $schemas;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function create(array $schemas = [], array $labels = []): Formula_Group {
    return new self($schemas, $labels);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $schemas
   * @param string[] $labels
   */
  public function __construct(array $schemas, array $labels) {

    foreach ($schemas as $k => $itemFormula) {
      if (!$itemFormula instanceof FormulaInterface) {
        throw new \InvalidArgumentException("Item schema at key $k must be instance of FormulaInterface.");
      }
    }

    $this->schemas = $schemas;
    $this->labels = $labels;
  }

  /**
   * @param string $key
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param null $label
   *
   * @return \Donquixote\OCUI\Formula\Group\Formula_Group
   */
  public function withItem(string $key, FormulaInterface $schema, $label = NULL): Formula_Group {
    $clone = clone $this;
    $clone->schemas[$key] = $schema;
    $clone->labels[$key] = $label ?? $key;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemFormulas(): array {
    return $this->schemas;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->labels;
  }
}
