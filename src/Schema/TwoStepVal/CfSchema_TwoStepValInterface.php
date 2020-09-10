<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\TwoStepVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\Cf\Zoo\V2V\TwoStep\V2V_TwoStepInterface;

interface CfSchema_TwoStepValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\TwoStep\CfSchema_TwoStepInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\TwoStep\V2V_TwoStepInterface
   */
  public function getV2V(): V2V_TwoStepInterface;

}
