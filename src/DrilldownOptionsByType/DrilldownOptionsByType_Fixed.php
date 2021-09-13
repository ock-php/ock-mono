<?php

namespace Donquixote\Ock\DrilldownOptionsByType;

class DrilldownOptionsByType_Fixed implements DrilldownOptionsByTypeInterface {

  /**
   * @var \Donquixote\Ock\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   */
  private $optionss;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Option\DrilldownOptionInterface[][] $optionss
   */
  public function __construct(array $optionss) {
    $this->optionss = $optionss;
  }

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array {
    return $this->optionss;
  }

}
