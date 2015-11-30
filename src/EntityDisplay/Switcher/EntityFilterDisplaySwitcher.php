<?php

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityFilter\EntityFilterInterface;

/**
 * @UniPlugin(
 *   id = "filterDisplaySwitcher",
 *   label = "Filter display switcher"
 * )
 */
class EntityFilterDisplaySwitcher extends EntitiesDisplayBase {

  /**
   * @var \Drupal\renderkit\EntityFilter\EntityFilterInterface
   */
  private $entityFilter;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $displayIfTrue;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null
   */
  private $displayIfFalse;

  /**
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $entityFilter
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $displayIfTrue
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null $displayIfFalse
   */
  function __construct(EntityFilterInterface $entityFilter, EntityDisplayInterface $displayIfTrue, EntityDisplayInterface $displayIfFalse = NULL) {
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
   * @param string $entity_type
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   */
  function buildEntities($entity_type, array $entities) {
    $qualityResult = $this->entityFilter->entitiesFilterDeltas($entity_type, $entities);
    $entitiesWithQuality = array();
    $entitiesWithoutQuality = array();
    $builds = array();
    foreach ($entities as $delta => $entity) {
      if (!empty($qualityResult[$delta])) {
        $entitiesWithQuality[$delta] = $entity;
      }
      else {
        $entitiesWithoutQuality[$delta] = $entity;
      }
      $builds[$delta] = array();
    }
    if ($entitiesWithQuality) {
      foreach ($this->displayIfTrue->buildEntities($entity_type, $entitiesWithQuality) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }
    if ($entitiesWithoutQuality) {
      foreach ($this->displayIfFalse->buildEntities($entity_type, $entitiesWithoutQuality) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }
    return $builds;
  }
}
