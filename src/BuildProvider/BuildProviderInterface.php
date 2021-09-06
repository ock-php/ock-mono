<?php
declare(strict_types=1);

namespace Drupal\renderkit\BuildProvider;

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
