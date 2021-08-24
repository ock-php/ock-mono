<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\DefaultConf;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_DefaultConfInterface extends FormulaInterface, Formula_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getDefaultConf();

}
