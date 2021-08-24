<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\StringVal;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\Zoo\V2V\String\V2V_StringInterface;

interface Formula_StringValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Textfield\Formula_TextfieldInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\Zoo\V2V\String\V2V_StringInterface
   */
  public function getV2V(): V2V_StringInterface;

}
