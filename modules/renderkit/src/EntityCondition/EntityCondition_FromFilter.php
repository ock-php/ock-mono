<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityFilter\EntityFilterInterface;
use Ock\Ock\Attribute\Parameter\OckAdaptee;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * Adapter from EntityFilterInterface to EntityConditionInterface.
 *
 * @todo Implement interface equivalence for cfrplugin.
 * @todo Mark as adapter / inline.
 *
 * @see \Drupal\renderkit\EntityFilter\EntityFilter_FromCondition
 */
#[OckPluginInstance('fromFilter', 'Filter')]
class EntityCondition_FromFilter implements EntityConditionInterface {

  /**
   * Adapter constructor.
   *
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $multiEntityFilter
   */
  public function __construct(
    #[OckAdaptee]
    private readonly EntityFilterInterface $multiEntityFilter,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function entityCheckCondition(EntityInterface $entity): bool {
    $entities = ['entity' => $entity];
    $filteredDeltas = $this->multiEntityFilter->entitiesFilterDeltas($entities);
    return $filteredDeltas === ['entity'];
  }

}
