<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema\Misc\EntityTypeCondition;

class EntityTypeCondition_EntityTypeId implements EntityTypeConditionInterface {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @param string $entityTypeId
   */
  public function __construct($entityTypeId) {
    $this->entityTypeId = $entityTypeId;
  }

  /**
   * @param string $entityTypeId
   *
   * @return bool
   */
  public function checkEntityTypeId($entityTypeId) {
    return $entityTypeId === $this->entityTypeId;
  }
}
