<?php

namespace Drupal\renderkit8\EntityToEntities;

abstract class EntityToEntitiesBase implements EntityToEntitiesInterface {

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
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
