<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\FixedConf;

use Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_FixedConfInterface extends Formula_OptionlessInterface, Formula_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getConf();

}
