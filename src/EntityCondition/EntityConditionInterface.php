<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityCondition;

use Drupal\Core\Entity\EntityInterface;

interface EntityConditionInterface {

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity);

}
