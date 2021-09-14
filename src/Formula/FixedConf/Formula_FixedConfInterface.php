<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\FixedConf;

use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_FixedConfInterface extends Formula_OptionlessInterface, Formula_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getConf();

}
