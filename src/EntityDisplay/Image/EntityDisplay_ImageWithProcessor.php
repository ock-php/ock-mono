<?php

namespace Drupal\renderkit8\EntityDisplay\Image;

use Drupal\renderkit8\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit8\EntityImage\EntityImageInterface;
use Drupal\renderkit8\ImageProcessor\ImageProcessorInterface;

class EntityDisplay_ImageWithProcessor extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityImage\EntityImageInterface
   */
  private $entityImageProvider;

  /**
   * @var \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface
   */
  private $imageProcessor;

  /**
   * @CfrPlugin(
   *   id = "processedEntityImage",
   *   label = @t("Image")
   * )
   *
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $entityImageProvider
   * @param \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface|NULL $imageProcessor
   *
   * @return \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  public static function create(EntityImageInterface $entityImageProvider, ImageProcessorInterface $imageProcessor = NULL) {
    return NULL !== $imageProcessor
      ? new static($entityImageProvider, $imageProcessor)
      : $entityImageProvider;
  }

  /**
   * ProcessedEntityImage constructor.
   *
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $entityImageProvider
   * @param \Drupal\renderkit8\ImageProcessor\ImageProcessorInterface $imageProcessor
   */
  public function __construct(EntityImageInterface $entityImageProvider, ImageProcessorInterface $imageProcessor) {
    $this->entityImageProvider = $entityImageProvider;
    $this->imageProcessor = $imageProcessor;
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {
    $rawBuilds = $this->entityImageProvider->buildEntities($entities);
    $processedBuilds = [];
    foreach ($rawBuilds as $delta => $rawBuild) {
      $processedBuilds[$delta] = $this->imageProcessor->processImage($rawBuild);
    }
    return $processedBuilds;
  }
}
