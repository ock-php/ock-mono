<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\DrilldownVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

interface CfSchema_DrilldownValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V(): V2V_DrilldownInterface;

}
