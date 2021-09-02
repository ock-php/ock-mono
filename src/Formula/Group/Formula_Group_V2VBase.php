<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Group;

use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VBase implements Formula_GroupInterface, V2V_GroupInterface {

  /**
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  public function getValFormula(): Formula_GroupValInterface {
    return new Formula_GroupVal($this, $this);
  }
}
