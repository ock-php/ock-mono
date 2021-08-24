<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\SequenceVal;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\ObCK\Zoo\V2V\Sequence\V2V_SequenceInterface;

interface Formula_SequenceValInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Sequence\Formula_SequenceInterface
   */
  public function getDecorated(): FormulaInterface;

  /**
   * @return \Donquixote\ObCK\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  public function getV2V(): V2V_SequenceInterface;

}
