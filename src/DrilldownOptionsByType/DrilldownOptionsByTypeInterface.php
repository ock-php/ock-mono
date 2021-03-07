<?php

namespace Donquixote\OCUI\DrilldownOptionsByType;

interface DrilldownOptionsByTypeInterface {

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array;

}
