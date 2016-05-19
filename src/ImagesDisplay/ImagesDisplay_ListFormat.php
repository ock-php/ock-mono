<?php

namespace Drupal\renderkit\ImagesDisplay;

use Drupal\renderkit\ImageProcessor\ImageProcessorInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;
use Drupal\renderkit\Util\RenderUtil;

/**
 * @CfrPlugin(
 *   id = "listFormat",
 *   label = "List format with image processor"
 * )
 */
class ImagesDisplay_ListFormat implements ImagesDisplayInterface {

  /**
   * @var \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null
   */
  private $imageProcessor;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface|null
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface $imageProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  function __construct(ImageProcessorInterface $imageProcessor = NULL, ListFormatInterface $listFormat = NULL) {
    $this->imageProcessor = $imageProcessor;
    $this->listFormat = $listFormat;
  }

  /**
   * @param array[] $images
   *   Format: $[$delta] = ['#theme' => 'image', '#path' => .., ..]
   *
   * @return array
   *   A Drupal render array.
   */
  function buildImages(array $images) {
    RenderUtil::validateImages($images);
    if ($this->imageProcessor !== NULL) {
      foreach ($images as $delta => $image) {
        $images[$delta] = $this->imageProcessor->processImage($image);
      }
    }
    if ($this->listFormat !== NULL) {
      $images = $this->listFormat->buildList($images);
    }
    return $images;
  }

}
