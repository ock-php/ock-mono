<?php
declare(strict_types=1);

namespace Drupal\renderkit\FieldDisplayProcessor;

class FieldDisplayProcessor_Bare implements FieldDisplayProcessorInterface {

  /**
   * @param array|array[][] $element
   *   Format:
   *     ['#theme' => 'field', '#items' => [..], ..]
   *     $['#items'][$delta] = $item
   *
   * @return array
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
