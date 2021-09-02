<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

interface V2V_GroupInterface {

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp): string;

}
