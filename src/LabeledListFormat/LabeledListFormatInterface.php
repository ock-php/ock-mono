<?php
declare(strict_types=1);

namespace Drupal\renderkit\LabeledListFormat;

interface LabeledListFormatInterface {

  /**
   * @param array[] $builds
   *   Render arrays, e.g. for field items or field group children.
   * @param string $label
   *   A label, e.g. for a field or field group.
   *
   * @return array
   *   Combined render array.
   */
  public function build(array $builds, $label): array;

}
