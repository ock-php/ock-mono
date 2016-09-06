<?php

namespace Drupal\renderkit\EntityToEntity;

class EntityToEntity_ChainOfTwo implements EntityToEntityInterface {

  /**
   * @var \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  private $first;

  /**
   * @var \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  private $second;

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $first
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $second
   */
  public function __construct(EntityToEntityInterface $first, EntityToEntityInterface $second) {
    $this->first = $first;
    $this->second = $second;
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType() {
    return $this->second->getTargetType();
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[]
   */
  public function entitiesGetRelated($entityType, array $entities) {
    $related = $this->first->entitiesGetRelated($entityType, $entities);
    return $this->second->entitiesGetRelated($this->first->getTargetType(), $related);
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  public function entityGetRelated($entityType, $entity) {
    $related = $this->first->entityGetRelated($entityType, $entity);
    if (NULL === $related) {
      return NULL;
    }
    return $this->second->entityGetRelated($this->first->getTargetType(), $related);
  }
}
