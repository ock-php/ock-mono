<?php

namespace Drupal\renderkit\EntityDisplay\Image;

use Drupal\renderkit\EntityDisplay\Image\NeutralImageDerivative;

/**
 * @see theme_image()
 */
abstract class ImageDerivativeBase extends NeutralImageDerivative {

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
      $builds[$delta] = $this->decorateImage($build, $entity_type, $entities[$delta]);
    }
    return $builds;
  }

  /**
   * @param array $build
   *   The render array produced by the decorated display handler.
   *   Contains '#theme' => 'image'.
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Modified render array for the given entity.
   */
  abstract protected function decorateImage($build, $entity_type, $entity);
}
