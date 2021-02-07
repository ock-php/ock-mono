<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaBase\Decorator;

use Donquixote\OCUI\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface Formula_DecoratorBaseInterface extends FormulaBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
