<?php

namespace Drupal\renderkit\EntityToEntity;

/**
 * Represents a one-to-one relation between entities.
 */
interface EntityToEntityInterface {

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  function getTargetType();

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[]
   */
  function entitiesGetRelated($entityType, array $entities);

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  function entityGetRelated($entityType, $entity);
}
