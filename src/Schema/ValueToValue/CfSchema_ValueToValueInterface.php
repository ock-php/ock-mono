<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\ValueToValue;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface;

interface CfSchema_ValueToValueInterface extends CfSchemaInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface
   */
  public function getV2V(): V2V_ValueInterface;

}
