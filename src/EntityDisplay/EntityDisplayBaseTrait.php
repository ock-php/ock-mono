<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

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
    $builds = [];
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $this->buildEntity($entity_type, $entity);
    }
    return $builds;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  abstract public function buildEntity(EntityInterface $entity);

}
