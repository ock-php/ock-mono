<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\BoolVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\Zoo\V2V\Boolean\V2V_BooleanInterface;

interface CfSchema_BoolValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Boolean\CfSchema_BooleanInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Boolean\V2V_BooleanInterface
   */
  public function getV2V(): V2V_BooleanInterface;

}
