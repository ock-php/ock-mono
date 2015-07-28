<?php

namespace Drupal\renderkit\EntityToEntities;

abstract class EntityToEntitiesBase implements EntityToEntitiesInterface {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[][]
   */
  function entitiesGetRelated($entityType, array $entities) {
    $targetEntities = array();
    foreach ($entities as $delta => $entity) {
      $targetEntities[$delta] = $this->entityGetRelated($entityType, $entity);
    }
    return $targetEntities;
  }

}
