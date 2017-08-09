<?php

namespace Drupal\renderkit8\EntityToEntities;

abstract class EntityToEntitiesMultipleBase implements EntityToEntitiesInterface {

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object[]
   */
  public function entityGetRelated($entityType, $entity) {
    $targetEntities = $this->entitiesGetRelated($entityType, [$entity]);
    return isset($targetEntities[0]) ? $targetEntities[0] : [];
  }
}
