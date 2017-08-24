<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\IdToSchema\IdToSchema_Callback;
use Donquixote\Cf\IdToSchema\IdToSchema_Fixed;
use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Donquixote\Cf\Schema\Select\CfSchema_Select_TwoStepFlatSelectComposite;
use Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName_StorageCondition extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @var \Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface
   */
  private $storageDefinitionCondition;

  public static function createEtDotFieldNameSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    FieldStorageDefinitionConditionInterface $storageDefinitionCondition,
    $storageConditionSignature
  ) {

    $etToSchema = self::createEtToSchema(
      $entityTypeId,
      $bundle,
      $storageDefinitionCondition,
      $storageConditionSignature);

    return new CfSchema_Select_TwoStepFlatSelectComposite(
      CfSchema_EntityType::create(),
      $etToSchema);
  }

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param \Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface $storageDefinitionCondition
   * @param string $storageConditionSignature
   *
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  public static function createEtToSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    FieldStorageDefinitionConditionInterface $storageDefinitionCondition,
    $storageConditionSignature
  ) {

    if (NULL === $entityTypeId) {
      return new IdToSchema_Callback(
        function($selectedEntityTypeId) use ($storageDefinitionCondition, $storageConditionSignature) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $storageDefinitionCondition,
            $storageConditionSignature);
        });
    }

    // Only allow this one entity type.
    return new IdToSchema_Fixed(
      [
        $entityTypeId => new self(
          $entityTypeId,
          $bundle,
          $storageDefinitionCondition,
          $storageConditionSignature)
      ]);
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   * @param \Drupal\renderkit8\Schema\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface $storageDefinitionCondition
   * @param string $storageConditionSignature
   */
  public function __construct(
    $entityType = NULL,
    $bundleName = NULL,
    FieldStorageDefinitionConditionInterface $storageDefinitionCondition,
    $storageConditionSignature
  ) {
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
    $this->storageDefinitionCondition = $storageDefinitionCondition;

    $signatureData = [
      $entityType,
      $bundleName,
      $storageConditionSignature,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:schema:field_name:storage_condition:' . $signature;

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

    $storages = $efm->getFieldStorageDefinitions($this->entityType);

    $fieldNamesByType = [];
    $fieldLabels = [];
    $fieldLabelsMissing = [];
    foreach ($storages as $fieldName => $storage) {

      if (!$this->storageDefinitionCondition->checkStorageDefinition($storage)) {
        continue;
      }

      $fieldTypeId = $storage->getType();

      if (NULL !== $label = $storage->getLabel()) {
        $fieldLabels[$fieldName] = $label;
      }
      else {
        $fieldLabels[$fieldName] = $fieldName;
        $fieldLabelsMissing[$fieldName] = TRUE;
      }

      $fieldNamesByType[$fieldTypeId][$fieldName] = $fieldName;
    }

    $moreLabels = $this->fieldNamesGetLabels($fieldLabelsMissing);

    $fieldLabels = array_replace($fieldLabels, $moreLabels);

    $groupedOptions = [];
    foreach ($fieldNamesByType as $fieldTypeId => $typeFieldNames) {

      if (NULL === $fieldType = $ftm->getDefinition($fieldTypeId, FALSE)) {
        continue;
      }

      $fieldTypeLabel = isset($fieldType['label'])
        ? $fieldType['label']
        : $fieldTypeId;

      foreach ($typeFieldNames as $fieldName) {
        $groupedOptions[$fieldTypeLabel][$fieldName] = isset($fieldLabels[$fieldName])
          ? $fieldLabels[$fieldName]
          : $fieldName;
      }
    }

    return $groupedOptions;
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
      $bfm->get($this->entityType, []),
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
        $this->entityType,
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
