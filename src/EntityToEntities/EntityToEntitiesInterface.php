<?php

namespace Drupal\renderkit\EntityToEntities;

/**
 * Represents a one-to-many relation between entities.
 */
interface EntityToEntitiesInterface {

  /**
   * @return string
   */
  function getTargetEntityType();

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[][]
   */
  function entitiesGetRelated($entityType, array $entities);

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object[]
   */
  function entityGetRelated($entityType, $entity);
}
