<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class Formula_Group implements Formula_GroupInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $formulas;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
   *
   * @return self
   */
  public static function create(array $formulas = [], array $labels = []): Formula_Group {
    return new self($formulas, $labels);
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $formulas
   * @param string[] $labels
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
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param null $label
   *
   * @return \Donquixote\OCUI\Formula\Group\Formula_Group
   */
  public function withItem(string $key, FormulaInterface $formula, $label = NULL): Formula_Group {
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
