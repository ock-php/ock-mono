<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Switcher;

use Drupal\renderkit\EntityDisplay\EntitiesDisplayBase;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityFilter\EntityFilterInterface;

/**
 * @ CfrPlugin(
 *   id = "conditional",
 *   label = "Conditional"
 * )
 */
class EntityDisplay_EntityFilter extends EntitiesDisplayBase {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $entityFilter
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $displayIfTrue
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface|null $displayIfFalse
   */
  public function __construct(
    private readonly EntityFilterInterface $entityFilter,
    private readonly EntityDisplayInterface $displayIfTrue,
    private readonly ?EntityDisplayInterface $displayIfFalse = NULL,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function buildEntities(array $entities): array {
    $deltas = $this->entityFilter->entitiesFilterDeltas($entities);
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
      foreach ($this->displayIfTrue->buildEntities($entitiesWithQuality) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }
    if ($entitiesWithoutQuality && $this->displayIfFalse) {
      foreach ($this->displayIfFalse->buildEntities($entitiesWithoutQuality) as $delta => $build) {
        $builds[$delta] = $build;
      }
    }

    return $builds;
  }
}
