<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\IdToFormula\IdToFormula_Callback;
use Donquixote\ObCK\IdToFormula\IdToFormula_Fixed;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Donquixote\ObCK\Formula\Select\Formula_Select_TwoStepFlatSelectComposite;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
class Formula_FieldName_StorageCondition extends Formula_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @var \Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface
   */
  private $storageDefinitionCondition;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param \Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface $storageDefinitionCondition
   * @param string $storageConditionSignature
   *
   * @return \Donquixote\ObCK\Formula\Select\Formula_SelectInterface
   */
  public static function createEtDotFieldNameFormula(
    $entityTypeId = NULL,
    $bundle = NULL,
    FieldStorageDefinitionConditionInterface $storageDefinitionCondition,
    $storageConditionSignature
  ): Formula_SelectInterface {

    $etToFormula = self::createEtToFormula(
      $entityTypeId,
      $bundle,
      $storageDefinitionCondition,
      $storageConditionSignature);

    return new Formula_Select_TwoStepFlatSelectComposite(
      Formula_EntityType::create(),
      $etToFormula);
  }

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param \Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface $storageDefinitionCondition
   * @param string $storageConditionSignature
   *
   * @return \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  public static function createEtToFormula(
    $entityTypeId = NULL,
    $bundle = NULL,
    FieldStorageDefinitionConditionInterface $storageDefinitionCondition,
    $storageConditionSignature
  ): IdToFormulaInterface {

    if (NULL === $entityTypeId) {
      return new IdToFormula_Callback(
        function($selectedEntityTypeId) use ($storageDefinitionCondition, $storageConditionSignature) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $storageDefinitionCondition,
            $storageConditionSignature);
        });
    }

    // Only allow this one entity type.
    return new IdToFormula_Fixed(
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
   * @param \Drupal\renderkit\Formula\Misc\FieldStorageDefinitionCondition\FieldStorageDefinitionConditionInterface $storageDefinitionCondition
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

    $cacheId = 'renderkit:formula:field_name:storage_condition:' . $signature;

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions(): array {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $storages = $efm->getFieldStorageDefinitions($this->entityType);

    /**
     * @var string[][] $fieldNamesByType
     *   Format: $[$fieldTypeId][$fieldName] = $fieldName
     * @var string[] $fieldLabels
     *   Format: $[$fieldName] = $fieldLabel
     * @var true[] $fieldLabelsMissing
     *   Format: $[$fieldName] = true
     */
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

      try {
        $fieldType = $ftm->getDefinition($fieldTypeId, false);
      }
      catch (PluginNotFoundException $e) {
        throw new \RuntimeException('Misbehaving FieldTypeManager: Exception thrown, even though $exception_on_invalid was false.', 0, $e);
      }

      if (NULL === $fieldType) {
        continue;
      }

      $fieldTypeLabel = $fieldType['label'] ?? $fieldTypeId;

      foreach ($typeFieldNames as $fieldName) {
        $groupedOptions[$fieldTypeLabel][$fieldName] = $fieldLabels[$fieldName] ?? $fieldName;
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
  private function fieldNamesGetLabels(array $fieldNamesMap): array {

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
      $bfm->get($this->entityType, []),
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
