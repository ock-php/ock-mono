<?php

namespace Drupal\renderkit8\EntityToRelatedIds;

use Drupal\Core\Entity\EntityInterface;

interface EntityToRelatedIdsInterface {

  /**
   * @return string
   */
  public function getTargetType();

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  public function entityGetRelatedIds(EntityInterface $entity);

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  public function entitiesGetRelatedIds(array $entities);

}
