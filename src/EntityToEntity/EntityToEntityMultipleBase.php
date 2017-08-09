<?php

namespace Drupal\renderkit8\EntityToEntity;

abstract class EntityToEntityMultipleBase implements EntityToEntityInterface {

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  public function entityGetRelated($entityType, $entity) {
    $targetEntities = $this->entitiesGetRelated($entityType, [$entity]);
    return isset($targetEntities[0]) ? $targetEntities[0] : NULL;
  }
}
