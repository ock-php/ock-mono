<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityToEntities;

abstract class EntityToEntitiesBase implements EntityToEntitiesInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[][]
   */
  public function entitiesGetRelated(array $entities) {

    $targetEntities = [];
    foreach ($entities as $delta => $entity) {
      $targetEntities[$delta] = $this->entityGetRelated($entity);
    }

    return $targetEntities;
  }

}
