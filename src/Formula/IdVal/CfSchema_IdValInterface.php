<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\IdVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface;

interface CfSchema_IdValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Id\CfSchema_IdInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface
   */
  public function getV2V(): V2V_IdInterface;

}
