<?php
declare(strict_types=1);

namespace Drupal\renderkit8\ImagesDisplay;

use Drupal\renderkit8\ImageProcessor\ImageProcessorInterface;
use Drupal\renderkit8\ListFormat\ListFormatInterface;
use Drupal\renderkit8\Util\RenderUtil;

/**
 * @CfrPlugin(
 *   id = "listFormat",
 *   label = "List format with image processor"
 * )
 */
class ImagesDisplay_ListFormat implements ImagesDisplayInterface {

  /**
   * @var \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface|null
   */
  private $imageProcessor;

  /**
   * @var \Drupal\renderkit8\ListFormat\ListFormatInterface|null
   */
  private $listFormat;

  /**
   * @param \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface $imageProcessor
   * @param \Drupal\renderkit8\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(ImageProcessorInterface $imageProcessor = NULL, ListFormatInterface $listFormat = NULL) {
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
  public function buildImages(array $images) {
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
