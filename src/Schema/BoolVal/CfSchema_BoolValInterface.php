<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\BoolVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Boolean\V2V_BooleanInterface;

interface CfSchema_BoolValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Schema\Boolean\CfSchema_BooleanInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Boolean\V2V_BooleanInterface
   */
  public function getV2V(): V2V_BooleanInterface;

}
