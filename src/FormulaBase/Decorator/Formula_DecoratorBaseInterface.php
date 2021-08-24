<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaBase\Decorator;

use Donquixote\ObCK\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_DecoratorBaseInterface extends FormulaBaseInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
