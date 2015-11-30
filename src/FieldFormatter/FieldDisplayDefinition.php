<?php

namespace Drupal\renderkit\FieldFormatter;

class FieldDisplayDefinition implements FieldDisplayDefinitionInterface {

  /**
   * @var array
   */
  private $display;

  /**
   * @param string $formatterName
   * @param array $formatterSettings
   *
   * @return static
   */
  static function create($formatterName, array $formatterSettings) {
    return new static(
      array(
        'type' => $formatterName,
        'settings' => $formatterSettings,
      ));
  }

  /**
   * @param array $display
   */
  function __construct(array $display) {
    $this->display = $display;
  }

  /**
   * Gets the array that defines the formatter and its settings.
   *
   * @return array
   */
  function getInfo() {
    return $this->display;
  }
}
