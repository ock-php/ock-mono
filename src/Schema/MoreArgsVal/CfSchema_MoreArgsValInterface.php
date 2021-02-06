<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\MoreArgsVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

interface CfSchema_MoreArgsValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgsInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
