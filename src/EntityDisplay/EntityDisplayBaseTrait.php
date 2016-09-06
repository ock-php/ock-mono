<?php

namespace Drupal\renderkit\EntityDisplay;

trait EntityDisplayBaseTrait {

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
  final public function buildEntities($entity_type, array $entities) {
    $builds = array();
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $this->buildEntity($entity_type, $entity);
    }
    return $builds;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  abstract public function buildEntity($entity_type, $entity);

}
