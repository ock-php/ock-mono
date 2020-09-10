<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Group;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface;
use Donquixote\Cf\Zoo\V2V\Group\V2V_GroupInterface;

abstract class CfSchema_Group_V2VBase implements CfSchema_GroupInterface, V2V_GroupInterface {

  /**
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public function getValSchema(): CfSchema_GroupValInterface {
    return new CfSchema_GroupVal($this, $this);
  }
}
