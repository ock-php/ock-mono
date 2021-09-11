<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\IdToFormula\IdToFormula_Callback;
use Donquixote\Ock\IdToFormula\IdToFormula_Fixed;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Donquixote\Ock\Formula\Proxy\Cache\Formula_Proxy_Cache_SelectBase;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Formula where the value is like 'body' for field 'node.body'.
 */
class Formula_BaseFieldName extends Formula_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function createEtDotFieldNameFormula(
    $entityTypeId = NULL,
    $bundle = NULL,
    array $allowedFieldTypes = NULL
  ): FormulaInterface {

    $etToFormula = self::createEtToFormula(
      $entityTypeId,
      $bundle,
      $allowedFieldTypes);

    $signatureData = [
      $entityTypeId,
      $bundle,
      $allowedFieldTypes,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:formula:et_dot_field_name:entity_reference:' . $signature;

    return new Formula_EtDotFieldName_EntityReference(
      $cacheId,
      $etToFormula);
  }

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string[]|null $allowedFieldTypes
   *
   * @return \Donquixote\Ock\IdToFormula\IdToFormulaInterface
   */
  public static function createEtToFormula(
    string $entityTypeId = NULL,
    string $bundle = NULL,
    array $allowedFieldTypes = NULL
  ): IdToFormulaInterface {

    if (NULL === $entityTypeId) {
      return new IdToFormula_Callback(
        function($selectedEntityTypeId) use ($allowedFieldTypes) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $allowedFieldTypes);
        });
    }

    // Only allow this one entity type.
    return new IdToFormula_Fixed(
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
    array $allowedFieldTypes = NULL
  ) {
    $this->entityTypeId = $entityTypeId;
    $this->bundleName = $bundleName;
    $this->allowedFieldTypes = $allowedFieldTypes;

    $signatureData = [
      $entityTypeId,
      $bundleName,
      $allowedFieldTypes,
    ];

    $signature = sha1(serialize($signatureData));

    $cacheId = 'renderkit:formula:field_name:allowed_types:' . $signature;

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

    $storages = $efm->getFieldStorageDefinitions($this->entityTypeId);

    if ([] === $storages) {
      return [];
    }

    $allowedTypesMap = NULL !== $this->allowedFieldTypes
      ? array_fill_keys($this->allowedFieldTypes, TRUE)
      : NULL;

    /**
     * @var string[][] $groupedOptionsPre0
     *   Format: $[$fieldTypeId][$fieldName] = $fieldName
     * @var string[] $fieldLabels
     *   Format: $[$fieldName] = $label
     * @var true[] $fieldLabelsMissing
     *   Format: $[$fieldName] = true
     */
    $groupedOptionsPre0 = [];
    $fieldLabels = [];
    $fieldLabelsMissing = [];
    foreach ($storages as $fieldName => $storage) {

      $fieldTypeId = $storage->getType();

      if (1
        && NULL !== $allowedTypesMap
        && !isset($allowedTypesMap[$fieldTypeId])
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

    /**
     * @var string[][] $groupedOptionsPre1
     *   Format: $[$fieldTypeId][$fieldName] = $fieldLabel
     */
    $groupedOptionsPre1 = [];
    foreach ($groupedOptionsPre0 as $fieldTypeId => $fieldNamesForType) {

      foreach ($fieldNamesForType as $fieldName) {

        $fieldLabel = $fieldLabels[$fieldName] ?? $fieldName;

        $groupedOptionsPre1[$fieldTypeId][$fieldName] = $fieldLabel;
      }
    }

    if (NULL === $allowedTypesMap || 1 < \count($allowedTypesMap)) {

      $groupedOptions = [];
      foreach ($groupedOptionsPre1 as $fieldTypeId => $fieldLabelsForType) {

        try {
          $fieldTypeDefinition = $ftm->getDefinition(
            $fieldTypeId,
            false);
        }
        catch (PluginNotFoundException $e) {
          // Direct contract violation.
          throw new \RuntimeException('Misbehaving FieldTypePluginManager::getDefinition(): Exception was thrown, even though $exception_on_invalid was false.', 0, $e);
        }

        if (null === $fieldTypeDefinition) {
          continue;
        }

        $fieldTypeLabel = isset($fieldTypeDefinition['label'])
          ? (string)$fieldTypeDefinition['label']
          : $fieldTypeId;

        foreach ($fieldLabelsForType as $fieldName => $fieldLabel) {
          $groupedOptions[$fieldTypeLabel][$fieldName] = $fieldLabel;
        }
      }

      return $groupedOptions;
    }

    if (1 < \count($groupedOptionsPre1)) {
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
