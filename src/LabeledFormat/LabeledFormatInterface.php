<?php
declare(strict_types=1);

namespace Drupal\renderkit8\LabeledFormat;

interface LabeledFormatInterface {

  /**
   * @param array $build
   *   Original render array (without label).
   * @param string $label
   *   A label / title.
   *
   * @return array
   *   Modified or wrapped render array with label.
   */
  public function buildAddLabel(array $build, $label);

}
