<?php

namespace Drupal\renderkit8\EntityToEntity;


abstract class EntityToEntityBase implements EntityToEntityInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entitiesGetRelated(array $entities) {
    $targetEntities = [];
    foreach ($entities as $delta => $entity) {
      if (NULL !== $targetEntity = $this->entityGetRelated($entity)) {
        $targetEntities[$delta] = $targetEntity;
      }
    }
    return $targetEntities;
  }
}
