<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\EntityTypeCondition;

class EntityTypeCondition_EntityTypeId implements EntityTypeConditionInterface {

  /**
   * @param string $entityTypeId
   */
  public function __construct(private $entityTypeId) {
  }

  /**
   * @param string $entityTypeId
   *
   * @return bool
   */
  public function checkEntityTypeId($entityTypeId): bool {
    return $entityTypeId === $this->entityTypeId;
  }
}
