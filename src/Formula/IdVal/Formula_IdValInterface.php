<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\IdVal;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\Zoo\V2V\Id\V2V_IdInterface;

interface Formula_IdValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Id\Formula_IdInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\Zoo\V2V\Id\V2V_IdInterface
   */
  public function getV2V(): V2V_IdInterface;

}
