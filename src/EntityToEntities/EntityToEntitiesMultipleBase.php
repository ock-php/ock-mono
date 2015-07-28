<?php

namespace Drupal\renderkit\EntityToEntities;

abstract class EntityToEntitiesMultipleBase implements EntityToEntitiesInterface {

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object[]
   */
  function entityGetRelated($entityType, $entity) {
    $targetEntities = $this->entitiesGetRelated($entityType, array($entity));
    return isset($targetEntities[0]) ? $targetEntities[0] : array();
  }
}
