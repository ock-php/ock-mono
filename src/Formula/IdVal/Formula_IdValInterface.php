<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\IdVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface;

interface Formula_IdValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Id\Formula_IdInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Id\V2V_IdInterface
   */
  public function getV2V(): V2V_IdInterface;

}
