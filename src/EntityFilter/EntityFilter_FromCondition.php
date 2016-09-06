<?php

namespace Drupal\renderkit\EntityFilter;

use Drupal\renderkit\EntityCondition\EntityConditionInterface;

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
 * @see \Drupal\renderkit\EntityCondition\EntityCondition_FromFilter
 */
class EntityFilter_FromCondition implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit\EntityCondition\EntityConditionInterface
   */
  private $singleEntityFilter;

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $entityCondition
   */
  public function __construct(EntityConditionInterface $entityCondition) {
    $this->singleEntityFilter = $entityCondition;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return string[]
   *   Format: $[] = $delta
   *   Deltas where the entity has the quality.
   */
  public function entitiesFilterDeltas($entityType, array $entities) {
    $deltas = array();
    foreach ($entities as $delta => $entity) {
      if ($this->singleEntityFilter->entityCheckCondition($entityType, $entity)) {
        $deltas[] = $delta;
      }
    }
    return $deltas;
  }
}
