<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

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
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity) {

    $related = $this->first->entityGetRelated($entity);

    if (NULL === $related) {
      return NULL;
    }

    return $this->second->entityGetRelated($related);
  }
}
