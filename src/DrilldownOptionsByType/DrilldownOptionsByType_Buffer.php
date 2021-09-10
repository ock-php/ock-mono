<?php

namespace Donquixote\ObCK\DrilldownOptionsByType;

class DrilldownOptionsByType_Buffer implements DrilldownOptionsByTypeInterface {

  /**
   * @var \Donquixote\ObCK\DrilldownOptionsByType\DrilldownOptionsByTypeInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface[][]|null
   */
  private $buffer;

  /**
   * @param \Donquixote\ObCK\DrilldownOptionsByType\DrilldownOptionsByTypeInterface $decorated
   */
  public function __construct(DrilldownOptionsByTypeInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * Gets drilldown options by type.
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Option\DrilldownOptionInterface[][]
   *   Format: $[$type][$id] = $option.
   */
  public function getDrilldownOptionsByType(): array {
    return $this->buffer
      ??= $this->decorated->getDrilldownOptionsByType();
  }

}
