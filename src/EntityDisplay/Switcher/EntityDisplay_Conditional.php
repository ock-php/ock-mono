<?php

namespace Drupal\renderkit8\EntityDisplay\Switcher;

use Drupal\renderkit8\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit8\EntityFilter\EntityFilterInterface;

/**
 * @CfrPlugin(
 *   id = "filterDisplaySwitcher",
 *   label = "Conditional entity display"
 * )
 */
class EntityDisplay_Conditional extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit8\EntityFilter\EntityFilterInterface
   */
  private $entityFilter;

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  private $displayIfTrue;

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface|null
   */
  private $displayIfFalse;

  /**
   * @param \Drupal\renderkit8\EntityFilter\EntityFilterInterface $entityFilter
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $displayIfTrue
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface|null $displayIfFalse
   */
  public function __construct(EntityFilterInterface $entityFilter, EntityDisplayInterface $displayIfTrue, EntityDisplayInterface $displayIfFalse = NULL) {
    $this->entityFilter = $entityFilter;
    $this->displayIfTrue = $displayIfTrue;
    $this->displayIfFalse = $displayIfFalse;
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  public function buildEntities($entityType, array $entities) {
    $deltas = $this->entityFilter->entitiesFilterDeltas($entityType, $entities);
    $lookup = array_fill_keys($deltas, TRUE);
    $entitiesWithQuality = [];
    $entitiesWithoutQuality = [];
    $builds = [];
    foreach ($entities as $delta => $entity) {
      if (!empty($lookup[$delta])) {
        $entitiesWithQuality[$delta] = $entity;
      }
      else {
        $entitiesWithoutQuality[$delta] = $entity;
      }
      $builds[$delta] = [];
    }
    if ($entitiesWithQuality) {
      foreach ($this->displayIfTrue->buildEntities($entityType, $entitiesWithQuality) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }
    if ($entitiesWithoutQuality && $this->displayIfFalse) {
      foreach ($this->displayIfFalse->buildEntities($entityType, $entitiesWithoutQuality) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }
    # \Drupal\krumong\dpm(get_defined_vars(), TRUE);
    return $builds;
  }
}
