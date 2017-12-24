<?php
declare(strict_types=1);

namespace Drupal\renderkit8\BuildProcessor;

/**
 * This is currently unused and untested!
 */
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
  public function __construct(array $classes = [], $attributesKey = '#attributes') {
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
  public function process(array $build) {

    if (!isset($build[$this->attributesKey])) {
      return $build;
    }

    if (!isset($build[$this->attributesKey]['class'])) {
      $build[$this->attributesKey]['class'] = $this->classes;
    }
    else {
      foreach ($this->classes as $class) {
        $build[$this->attributesKey]['class'][] = $class;
      }
    }

    return $build;
  }
}
