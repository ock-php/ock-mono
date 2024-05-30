<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Image;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityImage\EntityImageInterface;
use Drupal\renderkit\ImageProcessor\ImageProcessorInterface;
use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

class EntityDisplay_ImageWithProcessor extends EntitiesDisplayBase {

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $entityImageProvider
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|NULL $imageProcessor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  #[OckPluginInstance('image', 'Image')]
  public static function create(
    #[OckOption('image', 'Image')]
    EntityImageInterface $entityImageProvider,
    #[OckOption('image_processor', 'Image processor')]
    ImageProcessorInterface $imageProcessor = NULL,
  ): EntityDisplayInterface {
    return NULL !== $imageProcessor
      ? new static($entityImageProvider, $imageProcessor)
      : $entityImageProvider;
  }

  /**
   * ProcessedEntityImage constructor.
   *
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $entityImageProvider
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface $imageProcessor
   */
  public function __construct(
    private readonly EntityImageInterface $entityImageProvider,
    private readonly ImageProcessorInterface $imageProcessor,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {
    $rawBuilds = $this->entityImageProvider->buildEntities($entities);
    $processedBuilds = [];
    foreach ($rawBuilds as $delta => $rawBuild) {
      $processedBuilds[$delta] = $this->imageProcessor->processImage($rawBuild);
    }
    return $processedBuilds;
  }
}
