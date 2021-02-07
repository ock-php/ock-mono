<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Group;

use Donquixote\OCUI\Formula\GroupVal\Formula_GroupVal;
use Donquixote\OCUI\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VBase implements Formula_GroupInterface, V2V_GroupInterface {

  /**
   * @return \Donquixote\OCUI\Formula\GroupVal\Formula_GroupValInterface
   */
  public function getValFormula(): Formula_GroupValInterface {
    return new Formula_GroupVal($this, $this);
  }
}
