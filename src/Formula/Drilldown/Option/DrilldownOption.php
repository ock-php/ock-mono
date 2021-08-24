<?php

namespace Donquixote\ObCK\Formula\Drilldown\Option;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Select\Option\SelectOption;
use Donquixote\ObCK\Text\TextInterface;

class DrilldownOption extends SelectOption implements DrilldownOptionInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Text\TextInterface|null $label
   * @param \Donquixote\ObCK\Text\TextInterface|null $group_label
   */
  public function __construct(FormulaInterface $formula, ?TextInterface $label, ?TextInterface $group_label) {
    $this->formula = $formula;
    parent::__construct($label, $group_label);
  }

  public function getFormula(): FormulaInterface {
    return $this->formula;
  }

}
