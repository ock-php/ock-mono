<?php
declare(strict_types=1);

namespace Drupal\renderkit8\ImagesDisplay;

interface ImagesDisplayInterface {

  /**
   * @param array[] $images
   *   Format: $[$delta] = ['#theme' => 'image', '#path' => .., ..]
   *
   * @return array
   *   A Drupal render array.
   */
  public function buildImages(array $images);

}
