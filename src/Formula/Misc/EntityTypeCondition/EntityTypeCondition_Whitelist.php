<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\EntityTypeCondition;

class EntityTypeCondition_Whitelist implements EntityTypeConditionInterface {

  /**
   * @var true[]
   */
  private $truthsByEt;

  /**
   * @param string[] $entityTypeIds
   */
  public function __construct(array $entityTypeIds) {
    $this->truthsByEt = array_fill_keys($entityTypeIds, TRUE);
  }

  /**
   * @param string $entityTypeId
   *
   * @return bool
   */
  public function checkEntityTypeId($entityTypeId): bool {
    return isset($this->truthsByEt[$entityTypeId]);
  }
}
