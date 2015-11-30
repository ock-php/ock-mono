<?php

namespace Drupal\renderkit\EntityFilter;

use Drupal\renderkit\EntityCondition\EntityConditionInterface;

/**
 * Adapter from EntityConditionInterface to EntityFilterInterface.
 *
 * @UniPlugin()
 *
 * @todo Implement interface equivalence for uniplugin.
 *
 * @see \Drupal\renderkit\EntityCondition\FilterEntityCondition
 */
class ConditionEntityFilter implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit\EntityCondition\EntityConditionInterface
   */
  private $singleEntityFilter;

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $singleEntityFilter
   */
  function __construct(EntityConditionInterface $singleEntityFilter) {
    $this->singleEntityFilter = $singleEntityFilter;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return string[]
   *   Format: $[] = $delta
   *   Deltas where the entity has the quality.
   */
  function entitiesFilterDeltas($entityType, array $entities) {
    $deltas = array();
    foreach ($entities as $delta => $entity) {
      if ($this->singleEntityFilter->entityCheckCondition($entityType, $entity)) {
        $deltas[] = $delta;
      }
    }
    return $deltas;
  }
}
