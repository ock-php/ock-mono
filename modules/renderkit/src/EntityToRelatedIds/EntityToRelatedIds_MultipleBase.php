<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToRelatedIds;

use Drupal\Core\Entity\EntityInterface;

abstract class EntityToRelatedIds_MultipleBase implements EntityToRelatedIdsInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  final public function entityGetRelatedIds(EntityInterface $entity): array {
    $relatedIdsByDelta = $this->entitiesGetRelatedIds([$entity]);
    return array_key_exists(0, $relatedIdsByDelta)
      ? $relatedIdsByDelta[0]
      : [];
  }

}
