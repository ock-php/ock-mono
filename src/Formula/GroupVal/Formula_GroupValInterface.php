<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\GroupVal;

use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

interface Formula_GroupValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Group\Formula_GroupInterface
   */
  public function getDecorated(): Formula_GroupInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
