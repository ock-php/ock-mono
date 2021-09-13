<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;

class Formula_Group implements Formula_GroupInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface[]
   */
  private array $formulas;

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private array $labels;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $formulas
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
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
