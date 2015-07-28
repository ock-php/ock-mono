<?php

namespace Drupal\renderkit\EntityDisplay\EntityImageDerivative;

use Drupal\renderkit\EntityImage\EntityImageInterface;
use Drupal\renderkit\ImageDerivative\ImageDerivativeInterface;

class EntityImageProcessorDerivative extends NeutralEntityImageDerivative {

  /**
   * @var \Drupal\renderkit\ImageDerivative\ImageDerivativeInterface
   */
  private $imageDerivative;

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $imageProvider
   *   Object that extracts an image path from an entity, e.g. based on an image
   *   field, or the user picture, etc.
   * @param \Drupal\renderkit\ImageDerivative\ImageDerivativeInterface $imageDerivative
   */
  function __construct(EntityImageInterface $imageProvider, ImageDerivativeInterface $imageDerivative) {
    parent::__construct($imageProvider);
    $this->imageDerivative = $imageDerivative;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   * @throws \Exception
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = parent::buildMultiple($entity_type, $entities);
    foreach ($builds as $delta => $build) {
      $builds[$delta] = $this->imageDerivative->processImage($build);
    }
    return $builds;
  }
}
