<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntities;

use Drupal\Core\Entity\EntityInterface;

abstract class EntityToEntitiesMultipleBase implements EntityToEntitiesInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entityGetRelated(EntityInterface $entity): array {

    $targetEntities = $this->entitiesGetRelated([$entity]);

    return $targetEntities[0] ?? [];
  }
}
