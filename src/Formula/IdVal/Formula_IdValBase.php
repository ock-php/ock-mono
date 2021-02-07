<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\IdVal;

use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBase;

abstract class Formula_IdValBase extends Formula_DecoratorBase implements Formula_IdValInterface {

  /**
   * Same as parent, but requires an id schema.
   *
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $decorated
   */
  public function __construct(Formula_IdInterface $decorated) {
    parent::__construct($decorated);
  }

}
