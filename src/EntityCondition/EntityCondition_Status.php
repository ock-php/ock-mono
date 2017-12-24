<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityCondition;

use Drupal\Core\Entity\EntityInterface;

/**
 * Checks whether $entity->status === 1.
 *
 * @CfrPlugin(
 *   id = "status",
 *   label = "Entity is active / published"
 * )
 */
class EntityCondition_Status implements EntityConditionInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity) {
    return isset($entity->status) && ($entity->status === 1 || $entity->status === '1');
  }
}
