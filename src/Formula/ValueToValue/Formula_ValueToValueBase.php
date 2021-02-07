<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueToValue;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Label\Formula_Label;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;
use Donquixote\OCUI\Text\TextInterface;

abstract class Formula_ValueToValueBase extends Formula_DecoratorBase implements Formula_ValueToValueInterface {

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $label
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function withLabel(TextInterface $label): FormulaInterface {
    return new Formula_Label($this, $label);
  }

}
