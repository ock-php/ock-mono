<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\TwoStepVal;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\OCUI\Zoo\V2V\TwoStep\V2V_TwoStepInterface;

interface CfSchema_TwoStepValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\OCUI\Schema\TwoStep\CfSchema_TwoStepInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\TwoStep\V2V_TwoStepInterface
   */
  public function getV2V(): V2V_TwoStepInterface;

}
