<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\DefaultConf;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_DefaultConfInterface extends FormulaInterface, Formula_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getDefaultConf();

}
