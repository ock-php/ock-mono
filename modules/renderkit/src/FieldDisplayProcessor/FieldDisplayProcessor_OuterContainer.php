<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

class FieldDisplayProcessor_OuterContainer implements FieldDisplayProcessorInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   * @param list<string> $classes
   */
  public function __construct(
    private readonly FieldDisplayProcessorInterface $decorated,
    private readonly array $classes = [],
  ) {}

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element): array {

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
