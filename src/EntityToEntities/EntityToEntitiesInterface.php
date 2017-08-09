<?php

namespace Drupal\renderkit8\EntityToEntities;

/**
 * Represents a one-to-many relation between entities.
 */
interface EntityToEntitiesInterface {

  /**
   * @return string
   */
  public function getTargetEntityType();

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return object[][]
   */
  public function entitiesGetRelated($entityType, array $entities);

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return object[]
   */
  public function entityGetRelated($entityType, $entity);
}
