<?php

namespace Donquixote\OCUI\DrilldownOptionsByType;

class DrilldownOptionsByType_Fixed implements DrilldownOptionsByTypeInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   */
  private $optionss;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[][] $optionss
   */
  public function __construct(array $optionss) {
    $this->optionss = $optionss;
  }

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array {
    return $this->optionss;
  }

}
