<?php

namespace Donquixote\Ock\DrilldownOptionsByType;

class DrilldownOptionsByType_Buffer implements DrilldownOptionsByTypeInterface {

  /**
   * @var \Donquixote\Ock\DrilldownOptionsByType\DrilldownOptionsByTypeInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Ock\Formula\Drilldown\Option\DrilldownOptionInterface[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\Ock\DrilldownOptionsByType\DrilldownOptionsByTypeInterface $decorated
   */
  public function __construct(DrilldownOptionsByTypeInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array {
    return $this->buffer
      ??= $this->decorated->getDrilldownOptionsByType();
  }

}
