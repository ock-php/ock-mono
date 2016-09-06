<?php

namespace Drupal\renderkit\EntityToEntity;

abstract class EntityToEntityMultipleBase implements EntityToEntityInterface {

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  public function entityGetRelated($entityType, $entity) {
    $targetEntities = $this->entitiesGetRelated($entityType, array($entity));
    return isset($targetEntities[0]) ? $targetEntities[0] : NULL;
  }
}
