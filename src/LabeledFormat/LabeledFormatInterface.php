<?php

namespace Drupal\renderkit\LabeledFormat;

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
  function buildAddLabel(array $build, $label);

}
