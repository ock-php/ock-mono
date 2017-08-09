<?php

namespace Drupal\renderkit8\EntityFilter;

/**
 * Interface for entity filters.
 *
 * @see \Drupal\renderkit8\EntityCondition\EntityConditionInterface
 */
interface EntityFilterInterface {

  /**
   * Filters the entities based on a condition.
   *
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   *
   * @return string[]
   *   Format: $[] = $delta
   *   A filtered subset of the array keys of the $entities argument.
   */
  public function entitiesFilterDeltas($entityType, array $entities);

}
