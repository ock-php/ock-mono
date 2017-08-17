<?php

namespace Drupal\renderkit8\EntityDisplay\Images;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityDisplay\EntityDisplayBase;
use Drupal\renderkit8\EntityImages\EntityImagesInterface;
use Drupal\renderkit8\ImagesDisplay\ImagesDisplayInterface;

/**
 * @CfrPlugin(
 *   id = "images",
 *   label = "Images"
 * )
 */
class EntityDisplay_Images extends EntityDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityImages\EntityImagesInterface
   */
  private $entityImages;

  /**
   * @var \Drupal\renderkit8\ImagesDisplay\ImagesDisplayInterface
   */
  private $imagesDisplay;

  /**
   * @param \Drupal\renderkit8\EntityImages\EntityImagesInterface $entityImages
   * @param \Drupal\renderkit8\ImagesDisplay\ImagesDisplayInterface $imagesDisplay
   */
  public function __construct(EntityImagesInterface $entityImages, ImagesDisplayInterface $imagesDisplay) {
    $this->entityImages = $entityImages;
    $this->imagesDisplay = $imagesDisplay;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    $images = $this->entityImages->entityGetImages($entity);
    if ($images === []) {
      return [];
    }
    if (!is_array($images)) {
      $class = get_class($this->entityImages);
      throw new \RuntimeException("Injected $class->entityGetImages() component did not return an array.");
    }
    return $this->imagesDisplay->buildImages($images);
  }
}
