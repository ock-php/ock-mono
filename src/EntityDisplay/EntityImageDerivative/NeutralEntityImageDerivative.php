<?php

namespace Drupal\renderkit\EntityDisplay\EntityImageDerivative;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityImage\EntityImageInterface;

/**
 * A decorator class for entity image.
 *
 * @see \Drupal\renderkit\EntityDisplay\Wrapper\NeutralEntityWrapper
 */
class NeutralEntityImageDerivative implements EntityDisplayInterface {

  /**
   * The decorated image provider.
   *
   * @var \Drupal\renderkit\EntityImage\EntityImageInterface
   */
  private $imageProvider;

  /**
   * @param \Drupal\renderkit\EntityImage\EntityImageInterface $imageProvider
   *   Object that extracts an image path from an entity, e.g. based on an image
   *   field, or the user picture, etc.
   */
  function __construct(EntityImageInterface $imageProvider) {
    $this->imageProvider = $imageProvider;
  }

  /**
   * @param string $entity_type
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   * @throws \Exception
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = $this->imageProvider->buildMultiple($entity_type, $entities);
    foreach ($builds as $delta => $build) {
      if (empty($build)) {
        unset($builds[$delta]);
      }
      elseif (!isset($build['#theme']) || 'image' !== $build['#theme']) {
        throw new \Exception("Render arrays from image providers must use '#theme' => 'image'.");
      }
    }
    return $builds;
  }
}
