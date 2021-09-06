<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
abstract class Formula_FieldName_Base extends Formula_Proxy_Cache_SelectBase {

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

    $cacheId = 'renderkit:formula:field_name:' . $signature;

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions(): array {

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
  private function buildFieldLabels(array $storagesByType): array {

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
  private function buildTypeLabels(array $fieldTypeIds): array {

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $typeLabels = [];
    foreach ($fieldTypeIds as $fieldTypeId) {

      try {
        $fieldTypeDefinition = $ftm->getDefinition(
          $fieldTypeId,
          false);
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
  protected function getStorageDefinitionsByType(): array {

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
  private function getStorageDefinitions(): array {

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
  private function fieldNamesGetLabels(array $fieldNamesMap): array {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $kv */
    $kv = \Drupal::service('keyvalue');

    /** @var \Drupal\Core\KeyValueStore\KeyValueStoreInterface $bfm */
    $bfm = $kv->get('entity.definitions.bundle_field_map');

    /**
     * @var string[][][] $bundleFieldMaps
     *   Format: $[$fieldName]['bundles'][] = $bundle
     */
    $bundleFieldMaps = array_intersect_key(
      $bfm->get($this->entityTypeId, []),
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

    /**
     * @var string[][] $labelAliases
     *   Format: $[$fieldName][$label] = $label
     */
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

    /**
     * @var string[] $labels
     *   Format: $[$fieldName] = $label
     */
    $labels = [];
    foreach ($labelAliases as $fieldName => $fieldLabelAliases) {
      $labels[$fieldName] = implode(' | ', $fieldLabelAliases);
    }

    return $labels;
  }
}
