<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\DrilldownVal;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

interface Formula_DrilldownValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V(): V2V_DrilldownInterface;

}
