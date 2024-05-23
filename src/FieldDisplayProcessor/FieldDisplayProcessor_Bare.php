<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

/**
 * Only shows the raw field items, with no wrappers or label.
 */
class FieldDisplayProcessor_Bare implements FieldDisplayProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function process(array $element): array {
    $builds = [];
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }
    return $builds;
  }
}
