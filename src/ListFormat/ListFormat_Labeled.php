<?php

namespace Drupal\renderkit8\ListFormat;

use Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface;

class ListFormat_Labeled implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @var string
   */
  private $label;

  /**
   * @param \Drupal\renderkit8\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   * @param string $label
   */
  public function __construct(LabeledListFormatInterface $labeledListFormat, $label) {
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
  public function buildList(array $builds) {
    return $this->labeledListFormat->build($builds, $this->label);
  }
}
