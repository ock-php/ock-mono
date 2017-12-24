<?php
declare(strict_types=1);

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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   *
   * @return string[]
   *   Format: $[] = $delta
   */
  public function entitiesFilterDeltas(array $entities);

}
