<?php

namespace Drupal\renderkit\EntityImage;

use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Interface for entity image providers.
 *
 * For each entity, the image provider returns a render array with
 * '#theme' => 'image' and keys for alt and stuff, which can later be used to
 * build image styles and the like.
 */
interface EntityImageInterface extends EntityDisplayInterface {

  /**
   * Same method signature as in parent interface, just a different description.
   *
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *   Each render array must contain '#theme' => 'image'.
   */
  function buildMultiple($entity_type, array $entities);
}
