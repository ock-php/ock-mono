<?php

namespace Drupal\renderkit\EntityDisplay;

/**
 * Convenience base class for entity display handlers.
 *
 * Allows deriving classes to implement buildOne() instead of buildMultiple(),
 * which is usually easier.
 */
abstract class EntityDisplayBase implements EntityDisplayInterface {

  /**
   * Builds the render array for multiple entities, by using the method for a
   * single entity.
   *
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  final function buildMultiple($entity_type, array $entities) {
    $builds = array();
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $this->buildEntity($entity_type, $entity);
    }
    return $builds;
  }
}
