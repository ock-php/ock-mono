<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface;

interface CfSchema_DrilldownValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V(): V2V_DrilldownInterface;

}
