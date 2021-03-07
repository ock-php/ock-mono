<?php

namespace Donquixote\OCUI\Formula\Drilldown\Option;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Select\Option\SelectOption;
use Donquixote\OCUI\Text\TextInterface;

class DrilldownOption extends SelectOption implements DrilldownOptionInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\Text\TextInterface|null $label
   * @param \Donquixote\OCUI\Text\TextInterface|null $group_label
   */
  public function __construct(FormulaInterface $formula, ?TextInterface $label, ?TextInterface $group_label) {
    $this->formula = $formula;
    parent::__construct($label, $group_label);
  }

  public function getFormula(): FormulaInterface {
    return $this->formula;
  }

}
