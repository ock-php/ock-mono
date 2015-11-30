<?php

namespace Drupal\renderkit\EntityToRelatedIds;

abstract class EntityToRelatedIdsBase implements EntityToRelatedIdsInterface {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  final function entitiesGetRelatedIds($entityType, array $entities) {
    $relatedIdsByDelta = array();
    foreach ($entities as $delta => $entity) {
      $relatedIdsByDelta[$delta] = $this->entityGetRelatedIds($entityType, $entity);
    }
    return $relatedIdsByDelta;
  }
}
