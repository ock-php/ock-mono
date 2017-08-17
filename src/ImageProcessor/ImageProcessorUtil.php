<?php

namespace Drupal\renderkit8\ImageProcessor;

use Drupal\renderkit8\Util\UtilBase;

final class ImageProcessorUtil extends UtilBase {

  /**
   * @param array[] $images
   * @param \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface|null $imageProcessor
   *
   * @return array[]
   */
  public static function processImages(array $images, ImageProcessorInterface $imageProcessor = NULL) {

    if (NULL === $imageProcessor) {
      return $images;
    }

    foreach ($images as $delta => $image) {
      $images[$delta] = $imageProcessor->processImage($image);
    }

    return $images;
  }

}
