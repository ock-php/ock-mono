<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface;

interface CfSchema_ValueToValueInterface extends CfSchemaInterface, CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Value\V2V_ValueInterface
   */
  public function getV2V(): V2V_ValueInterface;

}
