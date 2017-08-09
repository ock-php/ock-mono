<?php

namespace Drupal\renderkit8\BuildProvider;

/**
 * Build providers generate a render array without any input.
 */
interface BuildProviderInterface {

  /**
   * @return array
   *   A render array.
   */
  public function build();
}
