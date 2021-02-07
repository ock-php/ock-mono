<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\SequenceVal;

use Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface;
use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBase;

abstract class Formula_SequenceValBase extends Formula_DecoratorBase implements Formula_SequenceValInterface {

  /**
   * Same as parent, but requires a sequence schema.
   *
   * @param \Donquixote\OCUI\Formula\Sequence\Formula_SequenceInterface $decorated
   */
  public function __construct(Formula_SequenceInterface $decorated) {
    parent::__construct($decorated);
  }

}
