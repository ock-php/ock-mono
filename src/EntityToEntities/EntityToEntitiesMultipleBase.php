<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntities;

abstract class EntityToEntitiesMultipleBase implements EntityToEntitiesInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entityGetRelated($entity): array {

    $targetEntities = $this->entitiesGetRelated([$entity]);

    return $targetEntities[0] ?? [];
  }
}
