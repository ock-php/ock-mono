<?php

namespace Drupal\renderkit\ValueToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter;

class ValueToValue_FieldEntityDisplay implements ValueToValueInterface {

  /**
   * @var string
   */
  private $fieldKey;

  /**
   * @var string
   */
  private $displayKey;

  /**
   * @var string
   */
  private $processorKey;

  /**
   * @param string $fieldKey
   * @param string $displayKey
   * @param string $processorKey
   */
  function __construct($fieldKey, $displayKey, $processorKey) {
    $this->fieldKey = $fieldKey;
    $this->displayKey = $displayKey;
    $this->processorKey = $processorKey;
  }

  /**
   * @param mixed $value
   *
   * @return mixed|\Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter
   */
  function valueGetValue($value) {
    if (!is_array($value)) {
      return new BrokenValue($this, get_defined_vars(), 'Should be an array..');
    }
    if (!isset($value[$this->fieldKey]) || !isset($value[$this->displayKey])) {
      return NULL;
    }
    return new EntityDisplay_FieldWithFormatter(
      $value[$this->fieldKey],
      $value[$this->displayKey],
      isset($value[$this->processorKey]) ? $value[$this->processorKey] : NULL);
  }
}
