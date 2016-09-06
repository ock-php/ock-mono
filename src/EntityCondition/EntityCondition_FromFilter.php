<?php

namespace Drupal\renderkit\EntityCondition;

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
   * @var \Drupal\renderkit\EntityFilter\EntityFilterInterface
   */
  private $multiEntityFilter;

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $multiEntityFilter
   */
  public function __construct(EntityFilterInterface $multiEntityFilter) {
    $this->multiEntityFilter = $multiEntityFilter;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  public function entityCheckCondition($entityType, $entity) {
    $entities = ['entity' => $entity];
    $filteredDeltas = $this->multiEntityFilter->entitiesFilterDeltas($entityType, $entities);
    return $filteredDeltas === ['entity'];
  }
}
