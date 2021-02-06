<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueToValue;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface;

interface Formula_ValueToValueInterface extends FormulaInterface, Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Value\V2V_ValueInterface
   */
  public function getV2V(): V2V_ValueInterface;

}
