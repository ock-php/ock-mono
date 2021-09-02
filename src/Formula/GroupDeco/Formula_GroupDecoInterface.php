<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\GroupDeco;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;

interface Formula_GroupDecoInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Group\Formula_GroupInterface
   */
  public function getDecorated(): Formula_GroupInterface;

  /**
   * @param string $php
   *   PHP to be decorated.
   *
   * @return \Donquixote\ObCK\V2V\Group\V2V_GroupInterface
   */
  public function phpGetV2V(string $php): V2V_GroupInterface;

}
