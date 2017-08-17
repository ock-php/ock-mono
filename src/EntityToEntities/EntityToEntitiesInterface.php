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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[][]
   */
  public function entitiesGetRelated(array $entities);

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entityGetRelated($entity);
}
