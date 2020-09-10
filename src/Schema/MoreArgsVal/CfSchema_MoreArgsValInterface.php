<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\MoreArgsVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

interface CfSchema_MoreArgsValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
