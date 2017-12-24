<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema\Misc\SelectByEt;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;

class SelectByEt_FieldName_EntityReference extends SelectByEt_FieldName_Base {

  /**
   * @return string
   */
  public function getCacheId() {
    return static::class;
  }

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[$bundleName] = $bundleName, or NULL to not restrict by bundle.
   *
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[][]
   *   Format: $[$fieldTypeId][$fieldName] = $fieldStorageDefinition
   */
  protected function getStorageDefinitionsByType($entityTypeId, array $bundleNames = NULL) {

    $storagesByType = parent::getStorageDefinitionsByType(
      $entityTypeId,
      $bundleNames);

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $storagesByTypeFiltered = [];
    foreach ($storagesByType as $fieldTypeId => $storagesForType) {

      try {
        $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId);
      }
      catch (PluginNotFoundException $e) {
        throw new \RuntimeException('Misbehaving FieldTypeManager::getDefinition(): Exception thrown, even though $exception_on_invalid was false.', 0, $e);
      }

      if (NULL === $fieldTypeDefinition) {
        continue;
      }

      if (!isset($fieldTypeDefinition['class'])) {
        continue;
      }

      $class = $fieldTypeDefinition['class'];

      if (!class_exists($class)) {
        continue;
      }

      if (!is_a($class, EntityReferenceItem::class, TRUE)) {
        continue;
      }

      $storagesByTypeFiltered[$fieldTypeId] = $storagesForType;
    }

    return $storagesByTypeFiltered;
  }

}
