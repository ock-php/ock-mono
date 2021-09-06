<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

/**
 * Represents a one-to-one relation between entities.
 */
interface EntityToEntityInterface {

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType();

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity);
}
