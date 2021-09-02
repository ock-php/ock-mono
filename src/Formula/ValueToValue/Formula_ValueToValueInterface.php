<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\ValueToValue;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\V2V\Value\V2V_ValueInterface;

interface Formula_ValueToValueInterface extends FormulaInterface, Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\V2V\Value\V2V_ValueInterface
   */
  public function getV2V(): V2V_ValueInterface;

}
