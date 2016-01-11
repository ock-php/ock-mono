<?php

namespace Drupal\renderkit\EntityCondition;

/**
 * This is not annotated as CfrPlugin, because it is equivalent with
 *
 * @see \Drupal\renderkit\EntityFilter\EntityFilter_Negation
 */
class EntityCondition_Negation implements EntityConditionInterface {

  /**
   * @var \Drupal\renderkit\EntityCondition\EntityConditionInterface
   */
  private $negatedCondition;

  /**
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $negatedCondition
   */
  function __construct(EntityConditionInterface $negatedCondition) {
    $this->negatedCondition = $negatedCondition;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  function entityCheckCondition($entityType, $entity) {
    return !$this->negatedCondition->entityCheckCondition($entityType, $entity);
  }
}
