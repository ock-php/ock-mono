<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImagesDisplay;

use Drupal\renderkit\ImageProcessor\ImageProcessorInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;
use Drupal\renderkit\Util\RenderUtil;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @todo Mark as adapter?
 */
#[OckPluginInstance('listFormat', 'List format with image processor')]
class ImagesDisplay_ListFormat implements ImagesDisplayInterface {

  /**
   * @var \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null
   */
  private ?ImageProcessorInterface $imageProcessor;

  /**
   * @var \Drupal\renderkit\ListFormat\ListFormatInterface|null
   */
  private ?ListFormatInterface $listFormat;

  /**
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface $imageProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface $listFormat
   */
  public function __construct(ImageProcessorInterface $imageProcessor = NULL, ListFormatInterface $listFormat = NULL) {
    $this->imageProcessor = $imageProcessor;
    $this->listFormat = $listFormat;
  }

  /**
   * {@inheritdoc}
   */
  public function buildImages(array $images): array {
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
