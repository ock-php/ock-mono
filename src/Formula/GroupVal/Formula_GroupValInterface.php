<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\GroupVal;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface;

interface Formula_GroupValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Group\Formula_GroupInterface
   */
  public function getDecorated(): Formula_GroupInterface;

  /**
   * @return \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
