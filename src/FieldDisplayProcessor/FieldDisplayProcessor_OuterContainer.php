<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

class FieldDisplayProcessor_OuterContainer implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  private $decorated;

  /**
   * @var string[]
   */
  private $classes = [];

  /**
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   */
  public function __construct(FieldDisplayProcessorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string[] $classes
   *
   * @return static
   */
  public function withAdditionalClasses(array $classes) {
    $clone = clone $this;
    foreach ($classes as $class) {
      $clone->classes[] = $class;
    }
    return $clone;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element) {

    if ([] === $modified = $this->decorated->process($element)) {
      return [];
    }

    $classes = [
      'field',
      'field-name-' . str_replace('_', '-', $element['#field_name']),
    ];

    foreach ($this->classes as $class) {
      $classes[] = $class;
    }

    $modified = [
      '#type' => 'container',
      'inner' => $modified,
    ];

    if ([] !== $classes) {
      $modified['#attributes']['class'] = $classes;
    }

    return $modified;
  }
}
