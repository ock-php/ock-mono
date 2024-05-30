<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Drupal\Core\Entity\EntityInterface;

/**
 * @CfrPlugin(
 *   id = "negation",
 *   label = @t("Negation")
 * )
 *
 * @see \Drupal\renderkit\EntityFilter\EntityFilter_Negation
 */
class EntityCondition_Negation implements EntityConditionInterface {

  /**
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $negatedCondition
   */
  public function __construct(
    private readonly EntityConditionInterface $negatedCondition,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function entityCheckCondition(EntityInterface $entity): bool {
    return !$this->negatedCondition->entityCheckCondition($entity);
  }
}
