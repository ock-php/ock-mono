<?php

namespace Drupal\renderkit\EntityCondition;

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
   * @param object $entity
   *
   * @return bool
   */
  function entityCheckCondition($entityType, $entity) {
    # \Drupal\krumong\dpm($entity, __METHOD__);
    return isset($entity->status) && ($entity->status === 1 || $entity->status === '1');
  }
}
