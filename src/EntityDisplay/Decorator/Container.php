<?php

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Drupal\renderkit\EntityDisplay\Decorator\AttributesDecoratorBase;

class Container extends AttributesDecoratorBase {

  /**
   * @var string
   */
  protected $tagName = 'div';

  /**
   * @param string $tagName
   *
   * @return $this
   */
  function setTagName($tagName) {
    $this->tagName = $tagName;
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
    if ('div' === $this->tagName) {
      // Use theme_container().
      return array(
        $build,
        '#theme_wrappers' => array('container'),
        '#attributes' => $this->attributes,
      );
    }
    else {
      // Use #prefix and #suffix.
      // This is not as elegant, but unfortunately
      return array(
        $build,
        '#prefix' => '<' . $this->tagName . drupal_attributes($this->attributes) . '>',
        '#suffix' => '</' . $this->tagName . '>',
      );
    }
  }
}
