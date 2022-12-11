<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProcessor;

/**
 * This is currently unused and untested!
 */
class BuildProcessor_ClassAttribute implements BuildProcessorInterface {

  /**
   * @var string[]
   */
  private $classes;

  /**
   * @param string[] $classes
   * @param string $attributesKey
   */
  public function __construct(array $classes = [], private $attributesKey = '#attributes') {
    $this->classes = $classes;
  }

  /**
   * @param array $build
   *   Render array before the processing.
   *
   * @return array
   *   Render array after the processing.
   */
  public function process(array $build): array {

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
