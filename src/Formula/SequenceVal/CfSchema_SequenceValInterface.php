<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\SequenceVal;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface;

interface CfSchema_SequenceValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\OCUI\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  public function getV2V(): V2V_SequenceInterface;

}
