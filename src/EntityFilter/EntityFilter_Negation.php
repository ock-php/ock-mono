<?php

namespace Drupal\renderkit8\EntityFilter;

/**
 * @CfrPlugin(
 *   id = "negation",
 *   label = @t("Negation")
 * )
 */
class EntityFilter_Negation implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit8\EntityFilter\EntityFilterInterface
   */
  private $negatedFilter;

  /**
   * @param \Drupal\renderkit8\EntityFilter\EntityFilterInterface $negatedFilter
   */
  public function __construct(EntityFilterInterface $negatedFilter) {
    $this->negatedFilter = $negatedFilter;
  }

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
  public function entitiesFilterDeltas($entityType, array $entities) {
    foreach ($this->negatedFilter->entitiesFilterDeltas($entityType, $entities) as $delta) {
      unset($entities[$delta]);
    }
    return array_keys($entities);
  }
}
