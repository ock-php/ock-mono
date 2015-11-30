<?php

namespace Drupal\renderkit\EntityCondition;

/**
 * Checks whether $entity->status === 1.
 *
 * @UniPlugin()
 */
class StatusEntityCondition implements EntityConditionInterface{

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  function entityCheckCondition($entityType, $entity) {
    return isset($entity->status) && $entity->status === 1;
  }
}
