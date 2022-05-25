<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\Text;

class Formula_Group implements Formula_GroupInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface[] $formulas
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
   */
  public function __construct(
    private readonly array $formulas,
    private readonly array $labels,
  ) {
    Formula::validateMultiple($formulas);
    Text::validateMultiple($labels);
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
