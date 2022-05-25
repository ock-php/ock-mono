<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\Text;

/**
 * Group where each item has the same type.
 */
class Formula_Group_SameItemFormula implements Formula_GroupInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
   */
  public function __construct(
    private readonly FormulaInterface $formula,
    private readonly array $labels,
  ) {
    Text::validate(...$labels);
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
