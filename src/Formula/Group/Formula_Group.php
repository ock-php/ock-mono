<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

class Formula_Group implements Formula_GroupInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private $formulas;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface[] $formulas
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
   *
   * @return self
   */
  public static function create(array $formulas = [], array $labels = []): self {
    return new self($formulas, $labels);
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface[] $formulas
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
   */
  public function __construct(array $formulas, array $labels) {

    foreach ($formulas as $k => $itemFormula) {
      if (!$itemFormula instanceof FormulaInterface) {
        throw new \InvalidArgumentException("Item formula at key $k must be instance of FormulaInterface.");
      }
    }

    $this->formulas = $formulas;
    $this->labels = $labels;
  }

  /**
   * @param string $key
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param null $label
   *
   * @return static
   */
  public function withItem(string $key, FormulaInterface $formula, $label = NULL): self {
    $clone = clone $this;
    $clone->formulas[$key] = $formula;
    $clone->labels[$key] = $label ?? $key;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemFormulas(): array {
    return $this->formulas;
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->labels;
  }
}
