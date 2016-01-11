<?php

namespace Drupal\renderkit\BuildProcessor;

class BuildProcessor_ClassAttribute implements BuildProcessorInterface {

  /**
   * @var string[]
   */
  private $classes;

  /**
   * @var string
   */
  private $attributesKey;

  /**
   * @param string[] $classes
   * @param string $attributesKey
   */
  function __construct(array $classes = array(), $attributesKey = '#attributes') {
    $this->classes = $classes;
    $this->attributesKey = $attributesKey;
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  function process(array $build) {
    if (!isset($build[$this->attributesKey])) {
      return $build;
    }
    if (!isset($build[$this->attributesKey]['class'])) {
      $this->attributesKey['class'] = $this->classes;
    }
    else {
      foreach ($this->classes as $class) {
        $this->attributesKey['class'][] = $class;
      }
    }
    return $build;
  }
}
