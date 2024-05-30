<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityFilter;

use Drupal\renderkit\EntityCondition\EntityConditionInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Adapter from EntityConditionInterface to EntityFilterInterface.
 *
 * @todo Implement interface equivalence for cfrplugin.
 *
 * @see \Drupal\renderkit\EntityCondition\EntityCondition_FromFilter
 *
 * @todo Mark as adapter.
 */
#[OckPluginInstance('condition', 'Entity condition')]
class EntityFilter_FromCondition implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit\EntityCondition\EntityConditionInterface
   */
  private EntityConditionInterface $singleEntityFilter;

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $entityCondition
   */
  public function __construct(EntityConditionInterface $entityCondition) {
    $this->singleEntityFilter = $entityCondition;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return string[]|int[]
   *   Format: $[] = $delta
   */
  public function entitiesFilterDeltas(array $entities): array {
    $deltas = [];
    foreach ($entities as $delta => $entity) {
      if ($this->singleEntityFilter->entityCheckCondition($entity)) {
        $deltas[] = $delta;
      }
    }
    return $deltas;
  }
}
