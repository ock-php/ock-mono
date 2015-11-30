<?php

namespace Drupal\renderkit\EntityToRelatedIds;

interface EntityToRelatedIdsInterface {

  /**
   * @return string
   */
  function getTargetType();

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  function entityGetRelatedIds($entityType, $entity);

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  function entitiesGetRelatedIds($entityType, array $entities);

}
