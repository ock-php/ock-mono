<?php

namespace Drupal\renderkit8\EntityToRelatedIds;

interface EntityToRelatedIdsInterface {

  /**
   * @return string
   */
  public function getTargetType();

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  public function entityGetRelatedIds($entityType, $entity);

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  public function entitiesGetRelatedIds($entityType, array $entities);

}
