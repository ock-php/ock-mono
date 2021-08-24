<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\TwoStepVal;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\ObCK\Zoo\V2V\TwoStep\V2V_TwoStepInterface;

interface Formula_TwoStepValInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\TwoStep\Formula_TwoStepInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\Zoo\V2V\TwoStep\V2V_TwoStepInterface
   */
  public function getV2V(): V2V_TwoStepInterface;

}
