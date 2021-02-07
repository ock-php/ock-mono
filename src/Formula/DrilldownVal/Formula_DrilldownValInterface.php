<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DrilldownVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

interface Formula_DrilldownValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V(): V2V_DrilldownInterface;

}
