<?php

namespace Drupal\renderkit\EntityToEntity;


abstract class EntityToEntityBase implements EntityToEntityInterface {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[]
   */
  function entitiesGetRelated($entityType, array $entities) {
    $targetEntities = array();
    foreach ($entities as $delta => $entity) {
      if (NULL !== $targetEntity = $this->entityGetRelated($entityType, $entity)) {
        $targetEntities[$delta] = $targetEntity;
      }
    }
    return $targetEntities;
  }
}
