<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

class EntityToEntity_SelfOrOther implements EntityToEntityInterface {

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $decorated
   */
  public function __construct(
    private readonly EntityToEntityInterface $decorated,
  ) {}

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType(): string {
    return $this->decorated->getTargetType();
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity): ?EntityInterface {

    return $entity->getEntityTypeId() === $this->getTargetType()
      ? $entity
      : $this->decorated->entityGetRelated($entity);
  }

}
