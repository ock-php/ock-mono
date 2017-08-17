<?php

namespace Drupal\renderkit8\EntityCondition;

use Drupal\Core\Entity\EntityInterface;

/**
 * This is not annotated as CfrPlugin, because it is equivalent with
 *
 * @see \Drupal\renderkit8\EntityFilter\EntityFilter_Negation
 */
class EntityCondition_Negation implements EntityConditionInterface {

  /**
   * @var \Drupal\renderkit8\EntityCondition\EntityConditionInterface
   */
  private $negatedCondition;

  /**
   * @param \Drupal\renderkit8\EntityCondition\EntityConditionInterface $negatedCondition
   */
  public function __construct(EntityConditionInterface $negatedCondition) {
    $this->negatedCondition = $negatedCondition;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity) {
    return !$this->negatedCondition->entityCheckCondition($entity);
  }
}
