<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
abstract class CfSchema_FieldName_Base extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   * @param mixed $cacheIdSalt
   */
  public function __construct(
    $entityTypeId,
    $bundleName = NULL,
    $cacheIdSalt
  ) {
    $this->entityTypeId = $entityTypeId;
    $this->bundleName = $bundleName;

    $signatureData = [
      $entityTypeId,
      $bundleName,
      $cacheIdSalt,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:schema:field_name:' . $signature;

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions() {

    $storagesByType = $this->getStorageDefinitionsByType();

    $fieldLabels = $this->buildFieldLabels($storagesByType);

    $typeLabels = $this->buildTypeLabels(array_keys($storagesByType));

    return $this->buildGroupedOptions(
      $storagesByType,
      $fieldLabels,
      $typeLabels);
  }

  /**
   * @param \Drupal\Core\Field\FieldStorageDefinitionInterface[][] $storagesByType
   *
   * @return string[]
   */
  protected function buildFieldLabels(array $storagesByType) {

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
      $this->fieldNamesGetLabels($fieldLabelsMissing));
  }

  /**
   * @param string[] $fieldTypeIds
   *
   * @return string[]
   */
  protected function buildTypeLabels(array $fieldTypeIds) {

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $typeLabels = [];
    foreach ($fieldTypeIds as $fieldTypeId) {

      if (NULL === $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId)) {
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
  protected function buildGroupedOptions(array $storagesByType, array $fieldLabels, array $typeLabels) {

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
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[][]
   *   Format: $[$fieldTypeId][$fieldName] = $fieldStorageDefinition
   */
  protected function getStorageDefinitionsByType() {

    $storages = $this->getStorageDefinitions();

    $storagesByType = [];
    foreach ($storages as $fieldName => $storage) {
      $storagesByType[$storage->getType()][$fieldName] = $storage;
    }

    return $storagesByType;
  }

  /**
   * @return \Drupal\Core\Field\FieldStorageDefinitionInterface[]
   *   Format: $[$fieldName] = $fieldStorageDefinition
   */
  protected function getStorageDefinitions() {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    $storages = $efm->getFieldStorageDefinitions($this->entityTypeId);

    if (NULL === $this->bundleName) {
      return $storages;
    }

    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $kv = \Drupal::service('keyvalue');

    /** @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bfm */
    $bfm = $kv->get('entity.definitions.bundle_field_map');

    $bundleFieldMaps = $bfm->get($this->entityTypeId, []);

    $bundleFieldMaps = array_intersect_key(
      $bundleFieldMaps,
      $storages);

    foreach ($bundleFieldMaps as $fieldName => $fieldBundleMap) {
      if (!isset($fieldBundleMap['bundles'][$this->bundleName])) {
        unset($storages[$fieldName]);
      }
    }

    return $storages;
  }

  /**
   * @param true[] $fieldNamesMap
   *   Format: $[$fieldName] = TRUE
   *
   * @return string[]
   *   Format: [$fieldName] = $label
   */
  private function fieldNamesGetLabels(array $fieldNamesMap) {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $kv = \Drupal::service('keyvalue');

    /** @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bfm */
    $bfm = $kv->get('entity.definitions.bundle_field_map');

    $bundleFieldMaps = array_intersect_key(
      $bfm->get($this->entityTypeId, []),
      $fieldNamesMap);

    $bundles = [];
    foreach ($bundleFieldMaps as $fieldName => $fieldBundleMap) {
      foreach ($fieldBundleMap['bundles'] as $bundle) {
        $bundles[$bundle][$fieldName] = $fieldName;
      }
    }

    $labelAliases = [];
    foreach ($bundles as $bundle => $bundleFieldNames) {

      if (NULL !== $this->bundleName && $bundle !== $this->bundleName) {
        continue;
      }

      $bundleFieldDefinitions = $efm->getFieldDefinitions(
        $this->entityTypeId,
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
