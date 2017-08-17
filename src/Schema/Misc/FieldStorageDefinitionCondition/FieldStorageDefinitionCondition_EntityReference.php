<?php

namespace Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;

class FieldStorageDefinitionCondition_EntityReference implements FieldStorageDefinitionConditionInterface {

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storage
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storage) {

    $fieldTypeId = $storage->getType();

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    if (NULL === $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId)) {
      return FALSE;
    }

    if (!isset($fieldTypeDefinition['class'])) {
      return FALSE;
    }

    $class = $fieldTypeDefinition['class'];

    if (!class_exists($class)) {
      return FALSE;
    }

    if (!is_a($class, EntityReferenceItem::class, TRUE)) {
      return FALSE;
    }

    return TRUE;
  }
}
