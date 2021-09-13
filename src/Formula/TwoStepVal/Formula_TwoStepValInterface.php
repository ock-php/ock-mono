<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\TwoStepVal;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\Ock\V2V\TwoStep\V2V_TwoStepInterface;

interface Formula_TwoStepValInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Ock\Formula\TwoStep\Formula_TwoStepInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\Ock\V2V\TwoStep\V2V_TwoStepInterface
   */
  public function getV2V(): V2V_TwoStepInterface;

}
