<?php

namespace Drupal\renderkit\ImageProcessor;

class NeutralImageProcessor implements ImageProcessorInterface {

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  function processImage(array $build) {
    return $build;
  }
}
