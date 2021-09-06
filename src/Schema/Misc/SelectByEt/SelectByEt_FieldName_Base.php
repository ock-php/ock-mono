<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema\Misc\SelectByEt;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldDefinitionInterface;

abstract class SelectByEt_FieldName_Base implements SelectByEtInterface {

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[] = $bundleName, or NULL to not restrict by bundle.
   *   If an empty array is specified, only base fields will be returned.
   *
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel
   */
  public function etGetGroupedOptions($entityTypeId, array $bundleNames = NULL): array {

    if (NULL !== $bundleNames) {
      $bundleNames = array_combine($bundleNames, $bundleNames);
    }

    $storagesByType = $this->getStorageDefinitionsByType(
      $entityTypeId,
      $bundleNames);

    $fieldLabels = $this->buildFieldLabels(
      $storagesByType,
      $entityTypeId,
      $bundleNames);

    $typeLabels = $this->buildTypeLabels(
      array_keys($storagesByType));

    return $this->buildGroupedOptions(
      $storagesByType,
      $fieldLabels,
      $typeLabels);
  }

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface[][] $storagesByType
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[$bundleName] = $bundleName, or NULL to not restrict by bundle.
   *   If an empty array is specified, only base fields will be returned.
   *
   * @return string[]
   */
  private function buildFieldLabels(array $storagesByType, string $entityTypeId, array $bundleNames = NULL): array {

    $fieldLabels = [];
    $fieldLabelsMissing = [];
    foreach ($storagesByType as $fieldTypeId => $storagesForType) {
      foreach ($storagesForType as $fieldName => $storage) {

        if (!$storage instanceof FieldDefinitionInterface) {
          // This is a bundle field.
          $fieldLabels[$fieldName] = $fieldName;
          $fieldLabelsMissing[$fieldName] = TRUE;
        }
        elseif (NULL !== $label = $storage->getLabel()) {
          // This is a base field with a label.
          $fieldLabels[$fieldName] = $label;
        }
        else {
          // This is a base field without a label.
          $fieldLabels[$fieldName] = $fieldName;
        }
      }
    }

    return array_replace(
      $fieldLabels,
      $this->fieldNamesGetLabels(
        $fieldLabelsMissing,
        $entityTypeId,
        $bundleNames));
  }

  /**
   * @param string[] $fieldTypeIds
   *
   * @return string[]
   */
  private function buildTypeLabels(array $fieldTypeIds): array {

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $typeLabels = [];
    foreach ($fieldTypeIds as $fieldTypeId) {

      try {
        $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId);
      }
      catch (PluginNotFoundException $e) {
        throw new \RuntimeException('Misbehaving FieldTypeManager::getDefinition(): Exception thrown, even though $exception_on_invalid was false.', 0, $e);
      }

      if (NULL === $fieldTypeDefinition) {
        continue;
      }

      $typeLabels[$fieldTypeId] = isset($fieldTypeDefinition['label'])
        ? (string)$fieldTypeDefinition['label']
        : $fieldTypeId;
    }

    return $typeLabels;
  }

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface[][] $storagesByType
   * @param string[] $fieldLabels
   * @param string[] $typeLabels
   *
   * @return string[][]
   */
  private function buildGroupedOptions(array $storagesByType, array $fieldLabels, array $typeLabels): array {

    /**
     * @var string[][] $groupedOptions
     */
    $groupedOptions = [];
    foreach ($storagesByType as $fieldTypeId => $storagesForType) {

      if (!isset($typeLabels[$fieldTypeId])) {
        continue;
      }

      $typeLabel = $typeLabels[$fieldTypeId];

      foreach ($storagesForType as $fieldName => $storage) {

        if (!isset($fieldLabels[$fieldName])) {
          continue;
        }

        $groupedOptions[$typeLabel][$fieldName] = $fieldLabels[$fieldName];
      }
    }

    return $groupedOptions;
  }

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[$bundleName] = $bundleName, or NULL to not restrict by bundle.
   *
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[][]
   *   Format: $[$fieldTypeId][$fieldName] = $fieldStorageDefinition
   */
  protected function getStorageDefinitionsByType(string $entityTypeId, array $bundleNames = NULL): array {

    $storages = $this->etGetStorageDefinitions(
      $entityTypeId,
      $bundleNames);

    $storagesByType = [];
    foreach ($storages as $fieldName => $storage) {
      $storagesByType[$storage->getType()][$fieldName] = $storage;
    }

    return $storagesByType;
  }

  /**
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[$bundleName] = $bundleName, or NULL to not restrict by bundle.
   *   If an empty array is specified, only base fields will be returned.
   *
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[]
   *   Format: $[$fieldName] = $fieldStorageDefinition
   */
  private function etGetStorageDefinitions(string $entityTypeId, array $bundleNames = NULL): array {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    $storages = $efm->getFieldStorageDefinitions($entityTypeId);

    if (NULL === $bundleNames) {
      return $storages;
    }

    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $kv = \Drupal::service('keyvalue');

    /** @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bfm */
    $bfm = $kv->get('entity.definitions.bundle_field_map');

    $bundleFieldMaps = $bfm->get($entityTypeId, []);

    $bundleFieldMaps = array_intersect_key(
      $bundleFieldMaps,
      $storages);

    foreach ($bundleFieldMaps as $fieldName => $fieldBundleMap) {
      if ([] === array_intersect_key($fieldBundleMap['bundles'], $bundleNames)) {
        unset($storages[$fieldName]);
      }
    }

    return $storages;
  }

  /**
   * @param true[] $fieldNamesMap
   *   Format: $[$fieldName] = TRUE
   * @param string $entityTypeId
   * @param string[]|null $bundleNames
   *   Format: $[$bundleName] = $bundleName, or NULL to not restrict by bundle.
   *   If an empty array is specified, only base fields will be returned.
   *
   * @return string[]
   *   Format: [$fieldName] = $label
   */
  private function fieldNamesGetLabels(array $fieldNamesMap, string $entityTypeId, array $bundleNames = NULL): array {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $kv = \Drupal::service('keyvalue');

    /** @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bfm */
    $bfm = $kv->get('entity.definitions.bundle_field_map');

    /**
     * @var string[][][] $bundleFieldMaps
     *   Format: $[$fieldName]['bundles'][] = $bundleName
     */
    $bundleFieldMaps = array_intersect_key(
      $bfm->get($entityTypeId, []),
      $fieldNamesMap);

    /**
     * @var string[][] $bundles
     *   Format: $[$bundle][$fieldName] = $fieldName
     */
    $bundles = [];
    foreach ($bundleFieldMaps as $fieldName => $fieldBundleMap) {
      foreach ($fieldBundleMap['bundles'] as $bundle) {
        $bundles[$bundle][$fieldName] = $fieldName;
      }
    }

    if (NULL !== $bundleNames) {
      $bundles = array_intersect_key($bundles, $bundleNames);
    }

    $labelAliases = [];
    foreach ($bundles as $bundle => $bundleFieldNames) {

      $bundleFieldDefinitions = $efm->getFieldDefinitions(
        $entityTypeId,
        $bundle);

      foreach ($bundleFieldNames as $fieldName) {
        if (isset($bundleFieldDefinitions[$fieldName])) {
          $fieldDefinition = $bundleFieldDefinitions[$fieldName];
          if (NULL !== $label = $fieldDefinition->getLabel()) {
            $labelAliases[$fieldName][$label] = $label;
          }
        }
      }
    }

    $labels = [];
    foreach ($labelAliases as $fieldName => $fieldLabelAliases) {
      $labels[$fieldName] = implode(' | ', $fieldLabelAliases);
    }

    return $labels;
  }

}
