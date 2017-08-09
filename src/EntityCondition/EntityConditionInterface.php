<?php

namespace Drupal\renderkit8\EntityCondition;

interface EntityConditionInterface {

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  public function entityCheckCondition($entityType, $entity);

}
