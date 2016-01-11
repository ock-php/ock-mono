<?php

namespace Drupal\renderkit\IdValueToValue;



use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

class IdValueToValue_FormatterSettingsToFieldDisplaySettings implements IdValueToValueInterface {

  /**
   * @param string $formatterTypeName
   * @param mixed $formatterSettings
   *   Value from $this->idGetConfigurator($id)->confGetValue($conf)
   *
   * @return mixed
   *   Transformed or combined value.
   */
  function idValueGetValue($formatterTypeName, $formatterSettings) {
    return array(
      'type' => $formatterTypeName,
      'settings' => $formatterSettings,
    );
  }
}
