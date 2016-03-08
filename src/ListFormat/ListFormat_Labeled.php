<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

class ListFormat_Labeled implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @var string
   */
  private $label;

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   * @param string $label
   */
  function __construct(LabeledListFormatInterface $labeledListFormat, $label) {
    $this->labeledListFormat = $labeledListFormat;
    $this->label = $label;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  function buildList(array $builds) {
    return $this->labeledListFormat->build($builds, $this->label);
  }
}
