<?php

namespace Drupal\renderkit\LabeledListFormat;

use Drupal\renderkit\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin("listformat", "List format", inline = true)
 */
class LabeledListFormat_ListFormat implements LabeledListFormatInterface {

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
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  function build(array $builds, $label) {
    return $this->listFormat->buildList($builds);
  }
}
