<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\MoreArgsVal;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;

interface Formula_MoreArgsValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Ock\Formula\MoreArgs\Formula_MoreArgsInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\Ock\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
