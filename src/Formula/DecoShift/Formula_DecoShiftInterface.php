<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\DecoShift;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

/**
 * @see \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface
 * @see \Donquixote\Ock\Todo\DecoTodo
 */
interface Formula_DecoShiftInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
