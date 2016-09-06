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
  final public function entitiesGetRelatedIds($entityType, array $entities) {
    $relatedIdsByDelta = [];
    foreach ($entities as $delta => $entity) {
      $relatedIdsByDelta[$delta] = $this->entityGetRelatedIds($entityType, $entity);
    }
    return $relatedIdsByDelta;
  }
}
