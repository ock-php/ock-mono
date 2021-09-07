<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Text\Text;

class Formula_Group implements Formula_GroupInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private array $formulas;

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $labels;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface[] $formulas
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
   */
  public function __construct(array $formulas, array $labels) {
    Formula::validate(...$formulas);
    Text::validate(...$labels);
    $this->formulas = $formulas;
    $this->labels = $labels;
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
