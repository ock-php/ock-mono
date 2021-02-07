<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\TwoStepVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\OCUI\Zoo\V2V\TwoStep\V2V_TwoStepInterface;

interface Formula_TwoStepValInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\TwoStep\Formula_TwoStepInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\TwoStep\V2V_TwoStepInterface
   */
  public function getV2V(): V2V_TwoStepInterface;

}
