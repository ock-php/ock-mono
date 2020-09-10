<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\FixedConf;

use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_FixedConfInterface extends CfSchema_OptionlessInterface, CfSchema_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getConf();

}
