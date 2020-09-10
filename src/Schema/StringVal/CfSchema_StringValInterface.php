<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\StringVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface;

interface CfSchema_StringValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Textfield\CfSchema_TextfieldInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\String\V2V_StringInterface
   */
  public function getV2V(): V2V_StringInterface;

}
