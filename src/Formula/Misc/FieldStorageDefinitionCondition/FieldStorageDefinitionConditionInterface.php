<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition;

use Drupal\Core\Field\FieldStorageDefinitionInterface;

interface FieldStorageDefinitionConditionInterface {

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storageDefinition
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storageDefinition): bool;

}
