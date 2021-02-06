<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\ValueProvider;

class CfSchema_ValueProvider_Clone extends CfSchema_ValueProvider_FixedValue {

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
