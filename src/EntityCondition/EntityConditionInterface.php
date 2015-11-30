<?php

namespace Drupal\renderkit\EntityCondition;

interface EntityConditionInterface {

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  function entityCheckCondition($entityType, $entity);

}
