<?php

namespace Drupal\renderkit8\EntityToRelatedIds;

use Drupal\Core\Entity\EntityInterface;

abstract class EntitiesToRelatedIdsBase implements EntityToRelatedIdsInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  final public function entityGetRelatedIds(EntityInterface $entity) {
    $relatedIdsByDelta = $this->entitiesGetRelatedIds([$entity]);
    return array_key_exists(0, $relatedIdsByDelta)
      ? $relatedIdsByDelta[0]
      : [];
  }
}
