<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Value;

use Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

class V2V_Value_DrilldownFixedId implements V2V_ValueInterface {

  /**
   * @var \Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2vDrilldown;

  /**
   * @var string
   */
  private $id;

  /**
   * @param \Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface $v2vDrilldown
   * @param string|int $id
   */
  public function __construct(V2V_DrilldownInterface $v2vDrilldown, $id) {
    $this->v2vDrilldown = $v2vDrilldown;
    $this->id = $id;
  }

  /**
   * {@inheritdoc}
   */
  public function phpGetPhp(string $php): string {
    return $this->v2vDrilldown->idPhpGetPhp($this->id, $php);
  }
}
