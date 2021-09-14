<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group;

use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

abstract class Formula_Group_V2VBase implements Formula_GroupInterface, V2V_GroupInterface {

  /**
   * @return \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface
   */
  public function getValFormula(): Formula_GroupValInterface {
    return new Formula_GroupVal($this, $this);
  }

}
