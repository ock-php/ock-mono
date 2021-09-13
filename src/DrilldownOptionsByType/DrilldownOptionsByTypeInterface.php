<?php

namespace Donquixote\Ock\DrilldownOptionsByType;

interface DrilldownOptionsByTypeInterface {

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array;

}
