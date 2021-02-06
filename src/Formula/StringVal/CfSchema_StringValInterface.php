<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\StringVal;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface;

interface CfSchema_StringValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Textfield\CfSchema_TextfieldInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface
   */
  public function getV2V(): V2V_StringInterface;

}
