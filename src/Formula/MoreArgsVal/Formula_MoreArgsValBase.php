<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\MoreArgsVal;

use Donquixote\OCUI\Formula\MoreArgs\Formula_MoreArgsInterface;
use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBase;

abstract class Formula_MoreArgsValBase extends Formula_DecoratorBase implements Formula_MoreArgsValInterface {

  /**
   * Same as parent, but must be a MoreArgs formula.
   *
   * @param \Donquixote\OCUI\Formula\MoreArgs\Formula_MoreArgsInterface $decorated
   */
  public function __construct(Formula_MoreArgsInterface $decorated) {
    parent::__construct($decorated);
  }
}
