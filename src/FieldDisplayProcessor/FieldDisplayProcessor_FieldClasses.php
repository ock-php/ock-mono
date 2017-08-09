<?php

namespace Drupal\renderkit8\FieldDisplayProcessor;

class FieldDisplayProcessor_FieldClasses implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit8\FieldDisplayProcessor\FieldDisplayProcessorInterface $decorated
   */
  public function __construct(FieldDisplayProcessorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element) {
    $build = $this->decorated->process($element);
    $build['#attributes']['class'][] = 'field';
    $build['#attributes']['class'][] = 'field-name-' . str_replace('_', '-', $element['#field_name']);
    $build['#attributes']['class'][] = 'field-type-' . str_replace('_', '-', $element['#field_type']);
    $build['#attributes']['class'][] = 'field-label-' . $element['#label_display'];
    return $build;
  }
}
