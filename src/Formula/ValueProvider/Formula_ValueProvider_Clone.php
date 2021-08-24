<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\ValueProvider;

class Formula_ValueProvider_Clone extends Formula_ValueProvider_FixedValue {

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    $value = parent::getValue();
    if (!is_object($value)) {
      return $value;
    }
    return clone $value;
  }

}
