<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

/**
 * @CfrPlugin("labeledListFormat", "Labeled list format")
 */
class FieldDisplayProcessor_LabeledListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   */
  public function __construct(LabeledListFormatInterface $labeledListFormat) {
    $this->labeledListFormat = $labeledListFormat;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  public function process(array $element) {

    $builds = [];
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }

    return $this->labeledListFormat->build($builds, $element['#title']);
  }
}
