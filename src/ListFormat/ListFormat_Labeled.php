<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface;

class ListFormat_Labeled implements ListFormatInterface {

  /**
   * @var \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface
   */
  private $labeledListFormat;

  /**
   * @param \Drupal\renderkit\LabeledListFormat\LabeledListFormatInterface $labeledListFormat
   * @param string $label
   */
  public function __construct(LabeledListFormatInterface $labeledListFormat, private $label) {
    $this->labeledListFormat = $labeledListFormat;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds): array {
    return $this->labeledListFormat->build($builds, $this->label);
  }
}
