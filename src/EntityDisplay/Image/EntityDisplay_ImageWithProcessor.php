<?php

namespace Drupal\renderkit\EntityDisplay\Image;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityImage\EntityImageInterface;
use Drupal\renderkit\ImageProcessor\ImageProcessorInterface;

class EntityDisplay_ImageWithProcessor extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityImage\EntityImageInterface
   */
  private $entityImageProvider;

  /**
   * @var \Drupal\renderkit\ImageProcessor\ImageProcessorInterface
   */
  private $imageProcessor;

  /**
   * @CfrPlugin(
   *   id = "processedEntityImage",
   *   label = @Translate("Image with processor")
   * )
   *
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $entityImageProvider
   * @param \Drupal\renderkit\ImageProcessor\ImageProcessorInterface|NULL $imageProcessor
   *
   * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  public static function create(EntityImageInterface $entityImageProvider, ImageProcessorInterface $imageProcessor = NULL) {
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
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  public function buildEntities($entityType, array $entities) {
    $rawBuilds = $this->entityImageProvider->buildEntities($entityType, $entities);
    $processedBuilds = array();
    foreach ($rawBuilds as $delta => $rawBuild) {
      $processedBuilds[$delta] = $this->imageProcessor->processImage($rawBuild);
    }
    return $processedBuilds;
  }
}
