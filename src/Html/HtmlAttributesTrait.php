<?php

namespace Drupal\renderkit\Html;

/**
 * @see AttributesInterface
 */
trait HtmlAttributesTrait {

  /**
   * @var mixed[]
   */
  protected $attributes = array();

  /**
   * @param string $class
   *
   * @return $this
   */
  public function addClass($class) {
    $this->attributes['class'][] = $class;
    return $this;
  }

  /**
   * @param string[] $classes
   *
   * @return $this
   */
  public function addClasses(array $classes) {
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
      /* @see themekit_element_info() */
      /* @see theme_themekit_container() */
      '#type' => 'themekit_container',
      '#tag_name' => $tagName,
      '#attributes' => $this->attributes,
    );
  }

}
