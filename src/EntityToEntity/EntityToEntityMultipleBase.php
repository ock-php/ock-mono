<?php

namespace Drupal\renderkit8\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

abstract class EntityToEntityMultipleBase implements EntityToEntityInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity) {
    $targetEntities = $this->entitiesGetRelated([$entity]);
    return isset($targetEntities[0]) ? $targetEntities[0] : NULL;
  }
}
