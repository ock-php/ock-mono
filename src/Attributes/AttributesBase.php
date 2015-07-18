<?php

namespace Drupal\renderkit\Attributes;

abstract class AttributesBase {

  /**
   * @var mixed[]
   */
  private $attributes = array();

  /**
   * @param string $class
   *
   * @return $this
   */
  function addClass($class) {
    $this->attributes['class'][] = $class;
    return $this;
  }

  /**
   * @param string[] $classes
   *
   * @return $this
   */
  function addClasses(array $classes) {
    foreach ($classes as $class) {
      $this->attributes['class'][] = $class;
    }
    return $this;
  }

  /**
   * @return mixed[]
   */
  protected function getAttributes() {
    return $this->attributes;
  }

}
