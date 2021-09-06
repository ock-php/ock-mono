<?php
declare(strict_types=1);

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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   *
   * @return string[]|int[]
   *   Format: $[] = $delta
   */
  public function entitiesFilterDeltas(array $entities): array;

}
