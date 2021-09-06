<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImageProcessor;

use Drupal\renderkit\Util\UtilBase;

final class ImageProcessorUtil extends UtilBase {

  /**
   * @param array[] $images
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null $imageProcessor
   *
   * @return array[]
   */
  public static function processImages(array $images, ImageProcessorInterface $imageProcessor = NULL): array {

    if (NULL === $imageProcessor) {
      return $images;
    }

    foreach ($images as $delta => $image) {
      $images[$delta] = $imageProcessor->processImage($image);
    }

    return $images;
  }

}
