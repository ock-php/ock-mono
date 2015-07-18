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
   * @param string $entity_type
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildMultiple($entity_type, array $entities) {
    $builds = array();
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $this->buildOne($entity_type, $entity);
    }
    return $builds;
  }

  /**
   * @param string $entity_type
   * @param object $entity
   *
   * @return array
   *   Render array for one entity.
   */
  abstract protected function buildOne($entity_type, $entity);
}
