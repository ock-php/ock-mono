<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\IdToSchema\IdToSchema_Callback;
use Donquixote\Cf\IdToSchema\IdToSchema_Fixed;
use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName_AllowedTypes extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @var true[]
   */
  private $allowedFieldTypesMap;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createEtDotFieldNameSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ) {

    $etToSchema = self::createEtToSchema(
      $entityTypeId,
      $bundle,
      $allowedFieldTypes);

    $signatureData = [
      $entityTypeId,
      $bundle,
      $allowedFieldTypes,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:schema:et_dot_field_name:entity_reference:' . $signature;

    return new CfSchema_EtDotFieldName_EntityReference(
      $cacheId,
      $etToSchema);
  }

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  public static function createEtToSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ) {

    if (NULL === $entityTypeId) {
      return new IdToSchema_Callback(
        function($selectedEntityTypeId) use ($allowedFieldTypes) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $allowedFieldTypes);
        });
    }

    // Only allow this one entity type.
    return new IdToSchema_Fixed(
      [
        $entityTypeId => new self(
          $entityTypeId,
          $bundle,
          $allowedFieldTypes)
      ]);
  }

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   * @param string[]|null $allowedFieldTypes
   */
  public function __construct(
    $entityTypeId,
    $bundleName = NULL,
    $allowedFieldTypes = NULL
  ) {
    $this->entityTypeId = $entityTypeId;
    $this->bundleName = $bundleName;
    $this->allowedFieldTypesMap = array_fill_keys($allowedFieldTypes, TRUE);

    $signatureData = [
      $entityTypeId,
      $bundleName,
      $allowedFieldTypes,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:schema:field_name:entity_reference_field:' . $signature;

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions() {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $storages = $efm->getFieldStorageDefinitions($this->entityTypeId);

    if ([] === $storages) {
      return [];
    }

    $groupedOptionsPre0 = [];
    $fieldLabels = [];
    $fieldLabelsMissing = [];
    foreach ($storages as $fieldName => $storage) {

      $fieldTypeId = $storage->getType();

      if (1
        && NULL !== $this->allowedFieldTypesMap
        && !isset($this->allowedFieldTypesMap[$fieldTypeId])
      ) {
        continue;
      }

      if ($storage instanceof FieldDefinitionInterface) {
        if (NULL !== $label = $storage->getLabel()) {
          $fieldLabels[$fieldName] = $label;
        }
        else {
          $fieldLabels[$fieldName] = $fieldName;
        }
      }
      else {
        $fieldLabels[$fieldName] = $fieldName;
        $fieldLabelsMissing[$fieldName] = TRUE;
      }

      $groupedOptionsPre0[$fieldTypeId][$fieldName] = $fieldName;
    }

    if ([] === $groupedOptionsPre0) {
      return [];
    }

    $moreLabels = $this->fieldNamesGetLabels($fieldLabelsMissing);

    $fieldLabels = array_replace($fieldLabels, $moreLabels);

    $groupedOptionsPre1 = [];
    foreach ($groupedOptionsPre0 as $fieldTypeId => $fieldNamesForType) {

      foreach ($fieldNamesForType as $fieldName) {

        $fieldLabel = isset($fieldLabels[$fieldName])
          ? $fieldLabels[$fieldName]
          : $fieldName;

        $groupedOptionsPre1[$fieldTypeId][$fieldName] = $fieldLabel;
      }
    }

    if (1 < count($this->allowedFieldTypesMap)) {

      $groupedOptions = [];
      foreach ($groupedOptionsPre1 as $fieldTypeId => $fieldLabelsForType) {

        if (NULL === $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId)) {
          continue;
        }

        $fieldTypeLabel = isset($fieldTypeDefinition['label'])
          ? $fieldTypeDefinition['label']
          : $fieldTypeId;

        foreach ($fieldLabelsForType as $fieldName => $fieldLabel) {
          $groupedOptions[$fieldTypeLabel][$fieldName] = $fieldLabel;
        }
      }

      return $groupedOptions;
    }

    if (1 < count($groupedOptionsPre1)) {
      throw new \RuntimeException('Misbehaving algorithm.');
    }

    return ['' => reset($groupedOptionsPre1)];
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
