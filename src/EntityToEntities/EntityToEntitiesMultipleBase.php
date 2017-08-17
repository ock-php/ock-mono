<?php

namespace Drupal\renderkit8\EntityToEntities;

abstract class EntityToEntitiesMultipleBase implements EntityToEntitiesInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entityGetRelated($entity) {

    $targetEntities = $this->entitiesGetRelated([$entity]);

    return isset($targetEntities[0])
      ? $targetEntities[0]
      : [];
  }
}
