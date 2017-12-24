<?php
declare(strict_types=1);

namespace Drupal\renderkit8\ImageProcessor;

class ImageProcessor_Neutral implements ImageProcessorInterface {

  /**
   * @param array $build
   *   Render array with '#theme' => 'image'.
   *
   * @return array
   *   Render array after the processing.
   */
  public function processImage(array $build) {
    return $build;
  }
}
