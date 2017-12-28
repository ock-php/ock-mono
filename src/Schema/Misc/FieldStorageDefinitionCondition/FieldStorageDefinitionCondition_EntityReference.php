<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;

class FieldStorageDefinitionCondition_EntityReference implements FieldStorageDefinitionConditionInterface {

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storageDefinition
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storageDefinition): bool {

    $fieldTypeId = $storageDefinition->getType();

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    try {
      $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId);
    }
    catch (PluginNotFoundException $e) {
      throw new \RuntimeException('Misbehaving FieldTypeManager::getDefinition(): Exception thrown, even though $exception_on_invalid was false.', 0, $e);
    }

    if (NULL === $fieldTypeDefinition) {
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
