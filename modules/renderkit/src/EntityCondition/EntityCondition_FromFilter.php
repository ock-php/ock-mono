<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityFilter\EntityFilterInterface;

/**
 * Adapter from EntityFilterInterface to EntityConditionInterface.
 *
 * @CfrPlugin(
 *   id = "fromFilter",
 *   label = "Filter",
 *   inline = true
 * )
 *
 * @todo Implement interface equivalence for cfrplugin.
 *
 * @see \Drupal\renderkit\EntityFilter\EntityFilter_FromCondition
 */
class EntityCondition_FromFilter implements EntityConditionInterface {

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $multiEntityFilter
   */
  public function __construct(
    private readonly EntityFilterInterface $multiEntityFilter,
  ) {}

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity): bool {
    $entities = ['entity' => $entity];
    $filteredDeltas = $this->multiEntityFilter->entitiesFilterDeltas($entities);
    return $filteredDeltas === ['entity'];
  }
}
