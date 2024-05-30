<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Images;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayBase;
use Drupal\renderkit\EntityImages\EntityImagesInterface;
use Drupal\renderkit\ImagesDisplay\ImagesDisplayInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('images', 'Images')]
class EntityDisplay_Images extends EntityDisplayBase {

  /**
   * @param \Drupal\renderkit\EntityImages\EntityImagesInterface $entityImages
   * @param \Drupal\renderkit\ImagesDisplay\ImagesDisplayInterface $imagesDisplay
   */
  public function __construct(
    private readonly EntityImagesInterface $entityImages,
    private readonly ImagesDisplayInterface $imagesDisplay,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntity(EntityInterface $entity): array {
    $images = $this->entityImages->entityGetImages($entity);
    if ($images === []) {
      return [];
    }
    if (!\is_array($images)) {
      $class = \get_class($this->entityImages);
      throw new \RuntimeException("Injected {$class}->entityGetImages() component did not return an array.");
    }
    return $this->imagesDisplay->buildImages($images);
  }
}
