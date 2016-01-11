<?php

namespace Drupal\renderkit\EntityFilter;

/**
 * @CfrPlugin(
 *   id = "negation",
 *   label = @t("Negation")
 * )
 */
class EntityFilter_Negation implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit\EntityFilter\EntityFilterInterface
   */
  private $negatedFilter;

  /**
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $negatedFilter
   */
  function __construct(EntityFilterInterface $negatedFilter) {
    $this->negatedFilter = $negatedFilter;
  }

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
  function entitiesFilterDeltas($entityType, array $entities) {
    foreach ($this->negatedFilter->entitiesFilterDeltas($entityType, $entities) as $delta) {
      unset($entities[$delta]);
    }
    return array_keys($entities);
  }
}
