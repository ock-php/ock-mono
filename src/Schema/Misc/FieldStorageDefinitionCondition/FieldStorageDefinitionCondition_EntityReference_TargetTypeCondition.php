<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema\Misc\FieldStorageDefinitionCondition;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\renderkit\Schema\Misc\EntityTypeCondition\EntityTypeConditionInterface;

class FieldStorageDefinitionCondition_EntityReference_TargetTypeCondition extends FieldStorageDefinitionCondition_EntityReference {

  /**
   * @var \Drupal\renderkit\Schema\Misc\EntityTypeCondition\EntityTypeConditionInterface
   */
  private $targetTypeCondition;

  /**
   * @param \Drupal\renderkit\Schema\Misc\EntityTypeCondition\EntityTypeConditionInterface $targetTypeCondition
   */
  public function __construct(EntityTypeConditionInterface $targetTypeCondition) {
    $this->targetTypeCondition = $targetTypeCondition;
  }

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storageDefinition
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storageDefinition): bool {

    if (!parent::checkStorageDefinition($storageDefinition)) {
      return FALSE;
    }

    if (NULL === $targetTypeId = $storageDefinition->getSetting('target_type')) {
      return FALSE;
    }

    return $this->targetTypeCondition->checkEntityTypeId($targetTypeId);
  }
}
