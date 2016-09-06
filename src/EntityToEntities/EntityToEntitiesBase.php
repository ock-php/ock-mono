<?php

namespace Drupal\renderkit\EntityToEntities;

abstract class EntityToEntitiesBase implements EntityToEntitiesInterface {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[][]
   */
  public function entitiesGetRelated($entityType, array $entities) {
    $targetEntities = [];
    foreach ($entities as $delta => $entity) {
      $targetEntities[$delta] = $this->entityGetRelated($entityType, $entity);
    }
    return $targetEntities;
  }

}
