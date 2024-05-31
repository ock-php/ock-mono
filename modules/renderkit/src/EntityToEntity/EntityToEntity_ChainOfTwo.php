<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

class EntityToEntity_ChainOfTwo implements EntityToEntityInterface {

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $first
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $second
   */
  public function __construct(
    private readonly EntityToEntityInterface $first,
    private readonly EntityToEntityInterface $second,
  ) {}

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType(): string {
    return $this->second->getTargetType();
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity): ?EntityInterface {

    $related = $this->first->entityGetRelated($entity);

    if (NULL === $related) {
      return NULL;
    }

    return $this->second->entityGetRelated($related);
  }

}
