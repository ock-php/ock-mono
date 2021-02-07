<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\FixedConf;

use Donquixote\OCUI\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_FixedConfInterface extends Formula_OptionlessInterface, Formula_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getConf();

}
