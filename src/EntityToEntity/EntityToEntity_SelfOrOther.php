<?php

namespace Drupal\renderkit8\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

class EntityToEntity_SelfOrOther implements EntityToEntityInterface {

  /**
   * @var \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface $decorated
   */
  public function __construct(EntityToEntityInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType() {
    return $this->decorated->getTargetType();
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entitiesGetRelated(array $entities) {

    $targetEntities = [];
    $sourceEntities = [];
    foreach ($entities as $delta => $entity) {
      if ($entity->getEntityType() === $this->getTargetType()) {
        $targetEntities[$delta] = $entity;
      }
      else {
        $sourceEntities[$delta] = $entity;
        $targetEntities[$delta] = NULL;
      }
    }

    foreach ($this->decorated->entitiesGetRelated($sourceEntities) as $delta => $targetEntity) {
      $targetEntities[$delta] = $targetEntity;
    }

    return array_filter($targetEntities);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity) {

    return $entity->getEntityTypeId() === $this->getTargetType()
      ? $entity
      : $this->decorated->entityGetRelated($entity);
  }
}
