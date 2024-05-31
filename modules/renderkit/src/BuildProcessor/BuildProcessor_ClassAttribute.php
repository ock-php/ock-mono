<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProcessor;

/**
 * This is currently unused and untested!
 */
class BuildProcessor_ClassAttribute implements BuildProcessorInterface {

  /**
   * @param string[] $classes
   * @param string $attributesKey
   */
  public function __construct(
    private readonly array $classes = [],
    private readonly string $attributesKey = '#attributes',
  ) {}

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
