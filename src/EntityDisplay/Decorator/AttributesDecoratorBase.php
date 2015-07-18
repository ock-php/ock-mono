<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\Decorator\DecoratorBase;

class AttributesDecoratorBase extends DecoratorBase {

  /**
   * @var mixed[]
   */
  protected $attributes = array();

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
   * @param array $build
   * @param string $entity_type
   * @param object $entity
   *
   * @return array Render array for one entity.
   * Render array for one entity.
   */
  protected function decorateOne($build, $entity_type, $entity) {
    // Use theme_container().
    return array(
      $build,
      '#type' => 'container',
      '#attributes' => $this->attributes,
    );
  }

}
