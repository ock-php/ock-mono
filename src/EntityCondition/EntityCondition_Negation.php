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
   * @var \Drupal\renderkit\EntityCondition\EntityConditionInterface
   */
  private $negatedCondition;

  /**
   * @param \Drupal\renderkit\EntityCondition\EntityConditionInterface $negatedCondition
   */
  public function __construct(EntityConditionInterface $negatedCondition) {
    $this->negatedCondition = $negatedCondition;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity) {
    return !$this->negatedCondition->entityCheckCondition($entity);
  }
}
