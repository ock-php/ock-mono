<?php
declare(strict_types=1);

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
  public function __construct(EntityFilterInterface $negatedFilter) {
    $this->negatedFilter = $negatedFilter;
  }

  /**
   * Filters the entities based on a condition.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Format: $[$delta] = $entity
   *
   * @return string[]|int[]
   *   Format: $[] = $delta
   */
  public function entitiesFilterDeltas(array $entities): array {

    foreach ($this->negatedFilter->entitiesFilterDeltas($entities) as $delta) {
      unset($entities[$delta]);
    }

    return array_keys($entities);
  }
}
