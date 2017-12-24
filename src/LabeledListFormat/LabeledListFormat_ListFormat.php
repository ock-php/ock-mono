<?php
declare(strict_types=1);

namespace Drupal\renderkit8\LabeledListFormat;

use Drupal\renderkit8\ListFormat\ListFormatInterface;

/**
 * @CfrPlugin("listformat", "List format", inline = true)
 */
class LabeledListFormat_ListFormat implements LabeledListFormatInterface {

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface $listFormat
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
  public function build(array $builds, $label) {
    return $this->listFormat->buildList($builds);
  }
}
