<?php

namespace Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition;

use Drupal\Core\Field\FieldStorageDefinitionInterface;

interface FieldStorageDefinitionConditionInterface {

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storageDefinition
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storageDefinition);

}
