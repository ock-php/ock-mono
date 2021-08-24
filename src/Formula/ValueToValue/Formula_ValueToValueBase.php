<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\ValueToValue;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Label\Formula_Label;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBase;
use Donquixote\ObCK\Text\TextInterface;

abstract class Formula_ValueToValueBase extends Formula_DecoratorBase implements Formula_ValueToValueInterface {

  /**
   * @param \Donquixote\ObCK\Text\TextInterface $label
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function withLabel(TextInterface $label): FormulaInterface {
    return new Formula_Label($this, $label);
  }

}
