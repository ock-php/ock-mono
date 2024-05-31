<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Drupal\Core\Entity\EntityInterface;
use Ock\Ock\Attribute\Parameter\OckDecorated;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

/**
 * @see \Drupal\renderkit\EntityFilter\EntityFilter_Negation
 *
 * @todo Mark as decorator.
 */
#[OckPluginInstance('negation', 'Negation')]
class EntityCondition_Negation implements EntityConditionInterface {

  /**
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $negatedCondition
   */
  public function __construct(
    #[OckDecorated]
    private readonly EntityConditionInterface $negatedCondition,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function entityCheckCondition(EntityInterface $entity): bool {
    return !$this->negatedCondition->entityCheckCondition($entity);
  }

}
