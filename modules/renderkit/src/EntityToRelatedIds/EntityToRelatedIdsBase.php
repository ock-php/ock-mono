<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToRelatedIds;

abstract class EntityToRelatedIdsBase implements EntityToRelatedIdsInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  final public function entitiesGetRelatedIds(array $entities): array {

    $relatedIdsByDelta = [];
    foreach ($entities as $delta => $entity) {
      $relatedIdsByDelta[$delta] = $this->entityGetRelatedIds($entity);
    }

    return $relatedIdsByDelta;
  }

}
