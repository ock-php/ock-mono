<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\MultiStepVal;

use Donquixote\ObCK\Formula\MultiStep\Formula_MultiStepInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

interface Formula_MultiStepValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\MultiStep\Formula_MultiStepInterface
   */
  public function getDecorated(): Formula_MultiStepInterface;

  /**
   * @return \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;
}
