<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueToValue;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface;

interface CfSchema_ValueToValueInterface extends CfSchemaInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface
   */
  public function getV2V(): V2V_ValueInterface;

}
