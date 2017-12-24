<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

trait EntityDisplayBaseTrait {

  /**
   * Builds the render array for multiple entities, by using the method for a
   * single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   *
   * @see \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface::buildEntities()
   */
  final public function buildEntities(array $entities) {

    $builds = [];
    foreach ($entities as $delta => $entity) {
      $builds[$delta] = $this->buildEntity($entity);
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
