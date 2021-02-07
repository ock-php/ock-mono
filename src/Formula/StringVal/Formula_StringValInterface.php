<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\StringVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface;

interface Formula_StringValInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Textfield\Formula_TextfieldInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\String\V2V_StringInterface
   */
  public function getV2V(): V2V_StringInterface;

}
