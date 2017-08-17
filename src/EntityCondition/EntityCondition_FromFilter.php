<?php

namespace Drupal\renderkit8\EntityCondition;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityFilter\EntityFilterInterface;

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
 * @see \Drupal\renderkit8\EntityFilter\EntityFilter_FromCondition
 */
class EntityCondition_FromFilter implements EntityConditionInterface {

  /**
   * @var \Drupal\renderkit8\EntityFilter\EntityFilterInterface
   */
  private $multiEntityFilter;

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit8\EntityFilter\EntityFilterInterface $multiEntityFilter
   */
  public function __construct(EntityFilterInterface $multiEntityFilter) {
    $this->multiEntityFilter = $multiEntityFilter;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity) {
    $entities = ['entity' => $entity];
    $filteredDeltas = $this->multiEntityFilter->entitiesFilterDeltas($entities);
    return $filteredDeltas === ['entity'];
  }
}
