<?php

namespace Drupal\renderkit\IdValueToValue;

use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter;

use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

class IdValueToValue_FieldDisplaySettingsToFieldEntityDisplay implements IdValueToValueInterface {

  /**
   * @param string $fieldName
   * @param mixed $display
   *   Value from $this->idGetConfigurator($fieldName)->confGetValue($conf)
   *
   * @return mixed
   *   Transformed or combined value.
   */
  function idValueGetValue($fieldName, $display) {
    return new EntityDisplay_FieldWithFormatter($fieldName, $display);
  }
}
