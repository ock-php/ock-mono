<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Group;

use Donquixote\OCUI\Util\PhpUtil;

class V2V_Group_Trivial implements V2V_GroupInterface {

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return PhpUtil::phpArray($itemsPhp);
  }
}
