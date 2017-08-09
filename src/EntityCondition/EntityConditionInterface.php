<?php

namespace Drupal\renderkit8\EntityCondition;

interface EntityConditionInterface {

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition($entityType, $entity);

}
