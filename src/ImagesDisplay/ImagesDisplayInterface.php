<?php

namespace Drupal\renderkit\ImagesDisplay;

interface ImagesDisplayInterface {

  /**
   * @param array[] $images
   *   Format: $[$delta] = ['#theme' => 'image', '#path' => .., ..]
   *
   * @return array
   *   A Drupal render array.
   */
  function buildImages(array $images);

}
