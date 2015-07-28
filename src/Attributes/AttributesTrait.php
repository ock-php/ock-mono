<?php

namespace Drupal\renderkit\Attributes;

/**
 * @see AttributesInterface
 */
trait AttributesTrait {

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
   * @param string $tagName
   *
   * @return array
   */
  protected function tagNameBuildContainer($tagName) {
    return array(
      /* @see renderkit_element_info() */
      /* @see theme_renderkit_container() */
      '#type' => 'renderkit_container',
      '#tag_name' => $tagName,
      '#attributes' => $this->attributes,
    );
  }

}
