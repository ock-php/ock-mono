<?php

namespace Drupal\renderkit\FieldFormatter;

interface FieldDisplayDefinitionInterface {

  /**
   * Gets the array that defines the formatter and its settings.
   *
   * @return array
   */
  function getInfo();

}
