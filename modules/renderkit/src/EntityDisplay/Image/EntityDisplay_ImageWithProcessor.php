<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Image;

use Ock\Ock\Attribute\Parameter\OckOption;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;
use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityImage\EntityImageInterface;
use Drupal\renderkit\ImageProcessor\ImageProcessorInterface;

class EntityDisplay_ImageWithProcessor extends EntitiesDisplayBase {

  /**
   * @CfrPlugin(
   *   id = "processedEntityImage",
   *   label = @t("Image")
   * )
   *
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
  public function buildEntities(array $entities): array {
    $rawBuilds = $this->entityImageProvider->buildEntities($entities);
    $processedBuilds = [];
    foreach ($rawBuilds as $delta => $rawBuild) {
      $processedBuilds[$delta] = $this->imageProcessor->processImage($rawBuild);
    }
    return $processedBuilds;
  }
}
