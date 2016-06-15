<?php

namespace Drupal\renderkit\ImageProcessor;

use Drupal\cfrapi\Util\UtilBase;

final class ImageProcessorUtil extends UtilBase {

  /**
   * @param array[] $images
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null $imageProcessor
   *
   * @return array[]
   */
  public static function processImages(array $images, ImageProcessorInterface $imageProcessor = NULL) {
    if (NULL === $imageProcessor) {
      return $images;
    }
    foreach ($images as &$image) {
      $image = $imageProcessor->processImage($image);
    }
    return $images;
  }

}
