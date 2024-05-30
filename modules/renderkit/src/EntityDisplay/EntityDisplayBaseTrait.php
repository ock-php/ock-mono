<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;

trait EntityDisplayBaseTrait {

  /**
   * {@inheritdoc}
   */
  final public function buildEntities(array $entities): array {

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
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  abstract public function buildEntity(EntityInterface $entity): array;

}
