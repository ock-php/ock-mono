<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Ock\DrupalTesting\DrupalTesting;

class FieldStorageDefinitionCondition_EntityReference implements FieldStorageDefinitionConditionInterface {

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface $storageDefinition
   *
   * @return bool
   */
  public function checkStorageDefinition(FieldStorageDefinitionInterface $storageDefinition): bool {

    $fieldTypeId = $storageDefinition->getType();

    $ftm = DrupalTesting::service(FieldTypePluginManagerInterface::class);

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
