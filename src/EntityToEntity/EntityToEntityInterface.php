<?php

namespace Drupal\renderkit8\EntityToEntity;

/**
 * Represents a one-to-one relation between entities.
 */
interface EntityToEntityInterface {

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType();

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return object[]
   */
  public function entitiesGetRelated($entityType, array $entities);

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return object|null
   */
  public function entityGetRelated($entityType, $entity);
}
