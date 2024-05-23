<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

class FieldDisplayProcessor_FieldClasses implements FieldDisplayProcessorInterface {

  /**
   * @param \Drupal\renderkit\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   */
  public function __construct(
    private readonly FieldDisplayProcessorInterface $decorated,
  ) {}

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element): array {
    $build = $this->decorated->process($element);
    $build['#attributes']['class'][] = 'field';
    $build['#attributes']['class'][] = 'field-name-' . str_replace('_', '-', $element['#field_name']);
    $build['#attributes']['class'][] = 'field-type-' . str_replace('_', '-', $element['#field_type']);
    $build['#attributes']['class'][] = 'field-label-' . $element['#label_display'];
    return $build;
  }
}
