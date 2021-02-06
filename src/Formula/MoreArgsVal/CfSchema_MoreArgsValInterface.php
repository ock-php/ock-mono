<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\MoreArgsVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface;

interface CfSchema_MoreArgsValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\MoreArgs\CfSchema_MoreArgsInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Group\V2V_GroupInterface
   */
  public function getV2V(): V2V_GroupInterface;

}
