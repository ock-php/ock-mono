<?php
declare(strict_types=1);

namespace Drupal\renderkit\ImagesDisplay;

use Drupal\renderkit\ImageProcessor\ImageProcessorInterface;
use Drupal\renderkit\ListFormat\ListFormatInterface;
use Drupal\renderkit\Util\RenderUtil;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @todo Mark as adapter? Not really.
 */
#[OckPluginInstance('listFormat', 'List format with image processor')]
class ImagesDisplay_ListFormat implements ImagesDisplayInterface {

  /**
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|null $imageProcessor
   * @param \Drupal\renderkit\ListFormat\ListFormatInterface|null $listFormat
   */
  public function __construct(
    #[OckOption('imageProcessor', 'Image processor')]
    private readonly ?ImageProcessorInterface $imageProcessor = NULL,
    #[OckOption('listFormat', 'List format')]
    private readonly ?ListFormatInterface $listFormat = NULL,
  ) {}

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
