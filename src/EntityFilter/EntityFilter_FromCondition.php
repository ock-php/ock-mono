<?php

namespace Drupal\renderkit8\EntityFilter;

use Drupal\renderkit8\EntityCondition\EntityConditionInterface;

/**
 * Adapter from EntityConditionInterface to EntityFilterInterface.
 *
 * @CfrPlugin(
 *   id = "condition",
 *   label = "Entity condition",
 *   inline = true
 * )
 *
 * @todo Implement interface equivalence for cfrplugin.
 *
 * @see \Drupal\renderkit8\EntityCondition\EntityCondition_FromFilter
 */
class EntityFilter_FromCondition implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit8\EntityCondition\EntityConditionInterface
   */
  private $singleEntityFilter;

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit8\EntityCondition\EntityConditionInterface $entityCondition
   */
  public function __construct(EntityConditionInterface $entityCondition) {
    $this->singleEntityFilter = $entityCondition;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return string[]
   *   Format: $[] = $delta
   */
  public function entitiesFilterDeltas(array $entities) {
    $deltas = [];
    foreach ($entities as $delta => $entity) {
      if ($this->singleEntityFilter->entityCheckCondition($entity)) {
        $deltas[] = $delta;
      }
    }
    return $deltas;
  }
}
