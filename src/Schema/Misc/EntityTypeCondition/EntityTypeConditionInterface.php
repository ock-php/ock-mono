<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema\Misc\EntityTypeCondition;

interface EntityTypeConditionInterface {

  /**
   * @param string $entityTypeId
   *
   * @return bool
   */
  public function checkEntityTypeId($entityTypeId);

}
