<?php

namespace Drupal\renderkit8\EntityToRelatedIds;

abstract class EntityToRelatedIdsBase implements EntityToRelatedIdsInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  final public function entitiesGetRelatedIds(array $entities) {

    $relatedIdsByDelta = [];
    foreach ($entities as $delta => $entity) {
      $relatedIdsByDelta[$delta] = $this->entityGetRelatedIds($entity);
    }

    return $relatedIdsByDelta;
  }
}
