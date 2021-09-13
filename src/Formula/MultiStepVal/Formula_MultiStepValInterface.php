<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\MultiStepVal;

use Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

interface Formula_MultiStepValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Ock\Formula\MultiStep\Formula_MultiStepInterface
   */
  public function getDecorated(): Formula_MultiStepInterface;

  /**
   * @return \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;
}
