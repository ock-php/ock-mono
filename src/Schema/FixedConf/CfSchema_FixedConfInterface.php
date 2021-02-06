<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\FixedConf;

use Donquixote\OCUI\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_FixedConfInterface extends CfSchema_OptionlessInterface, CfSchema_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getConf();

}
