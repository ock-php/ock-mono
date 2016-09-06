<?php

namespace Drupal\renderkit\EntityFilter;

/**
 * Interface for entity filters.
 *
 * @see \Drupal\renderkit\EntityCondition\EntityConditionInterface
 */
interface EntityFilterInterface {

  /**
   * Filters the entities based on a condition.
   *
   * @param string $entityType
   * @param object[] $entities
   *   Format: $[$delta] = $entity
   *
   * @return string[]
   *   Format: $[] = $delta
   *   A filtered subset of the array keys of the $entities argument.
   */
  public function entitiesFilterDeltas($entityType, array $entities);

}
