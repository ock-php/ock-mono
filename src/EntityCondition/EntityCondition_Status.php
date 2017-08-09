<?php

namespace Drupal\renderkit8\EntityCondition;

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
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition($entityType, $entity) {
    # \Drupal\krumong\dpm($entity, __METHOD__);
    return isset($entity->status) && ($entity->status === 1 || $entity->status === '1');
  }
}
