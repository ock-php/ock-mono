<?php

namespace Drupal\renderkit\BuildProvider;

/**
 * Build providers generate a render array without any input.
 */
interface BuildProviderInterface {

  /**
   * @return array
   *   A render array.
   */
  function build();
}
