<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\GroupVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

interface CfSchema_GroupValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Schema\Group\CfSchema_GroupInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
