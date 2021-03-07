<?php

namespace Donquixote\OCUI\DrilldownOptionsByType;

class DrilldownOptionsByType_Buffer implements DrilldownOptionsByTypeInterface {

  /**
   * @var \Donquixote\OCUI\DrilldownOptionsByType\DrilldownOptionsByTypeInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\OCUI\DrilldownOptionsByType\DrilldownOptionsByTypeInterface $decorated
   */
  public function __construct(DrilldownOptionsByTypeInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array {
    return $this->buffer
      ?: $this->decorated->getDrilldownOptionsByType();
  }

}
