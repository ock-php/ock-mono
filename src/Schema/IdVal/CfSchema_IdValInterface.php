<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\IdVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\Zoo\V2V\Id\V2V_IdInterface;

interface CfSchema_IdValInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Id\V2V_IdInterface
   */
  public function getV2V(): V2V_IdInterface;

}
