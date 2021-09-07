<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Text\Text;

/**
 * Group where each item has the same type.
 */
class Formula_Group_SameItemFormula implements Formula_GroupInterface {

  /**
   * Formula for each item.
   *
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private FormulaInterface $formula;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $labels;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
   */
  public function __construct(FormulaInterface $formula, array $labels) {
    $this->formula = $formula;
    Text::validate(...$labels);
    $this->labels = $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function getItemFormulas(): array {
    return array_fill_keys(
      array_keys($this->labels),
      $this->formula);
  }

  /**
   * {@inheritdoc}
   */
  public function getLabels(): array {
    return $this->labels;
  }

}
