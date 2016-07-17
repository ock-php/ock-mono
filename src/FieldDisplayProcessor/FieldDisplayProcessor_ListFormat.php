<?php

namespace Drupal\renderkit\FieldDisplayProcessor;

use Drupal\renderkit\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin("listFormat", "List format", inline = true)
 */
class FieldDisplayProcessor_ListFormat implements FieldDisplayProcessorInterface {

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(ListFormatInterface $listFormat) {
    $this->listFormat = $listFormat;
  }

  /**
   * @param array $element
   *   Render array with ['#theme' => 'field', ..]
   *
   * @return array
   */
  function process(array $element) {

    $builds = array();
    foreach ($element['#items'] as $delta => $item) {
      if (!empty($element[$delta])) {
        $builds[$delta] = $element[$delta];
      }
    }

    return $this->listFormat->buildList($builds);
  }
}
