<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntities;

use Drupal\Core\Entity\EntityInterface;

/**
 * Represents a one-to-many relation between entities.
 */
interface EntityToEntitiesInterface {

  /**
   * @return string
   */
  public function getTargetEntityType(): string;

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[][]
   */
  public function entitiesGetRelated(array $entities): array;

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entityGetRelated(EntityInterface $entity): array;
}
