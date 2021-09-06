<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchema_Callback;
use Donquixote\Cf\IdToSchema\IdToSchema_Fixed;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName_EntityReference extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @var null|string
   */
  private $targetTypeId;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundle
   * @param string|null $targetTypeId
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createEtDotFieldNameSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    $targetTypeId = NULL
  ): CfSchemaInterface {

    $etToSchema = self::createEtToSchema(
      $entityTypeId,
      $bundle,
      $targetTypeId);

    $signatureData = [
      $entityTypeId,
      $bundle,
      $targetTypeId,
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
   * @param string|null $targetTypeId
   *
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  public static function createEtToSchema(
    $entityTypeId = NULL,
    $bundle = NULL,
    $targetTypeId = NULL
  ): IdToSchemaInterface {

    if (NULL === $entityTypeId) {
      return new IdToSchema_Callback(
        function($selectedEntityTypeId) use ($targetTypeId) {
          return new self(
            $selectedEntityTypeId,
            NULL,
            $targetTypeId);
        });
    }

    // Only allow this one entity type.
    return new IdToSchema_Fixed(
      [
        $entityTypeId => new self(
          $entityTypeId,
          $bundle,
          $targetTypeId)
      ]);
  }

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   * @param string|null $targetTypeId
   */
  public function __construct(
    $entityTypeId,
    $bundleName = NULL,
    $targetTypeId = NULL
  ) {
    $this->entityTypeId = $entityTypeId;
    $this->bundleName = $bundleName;
    $this->targetTypeId = $targetTypeId;

    $signatureData = [
      $entityTypeId,
      $bundleName,
      $targetTypeId,
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
  protected function getGroupedOptions(): array {

    /** @var \Drupal\Core\Entity\EntityTypeRepositoryInterface $etr */
    $etr = \Drupal::service('entity_type.repository');

    $entityTypeLabels = $etr->getEntityTypeLabels();

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $efm */
    $efm = \Drupal::service('entity_field.manager');

    /** @var \Drupal\Core\Field\FieldTypePluginManagerInterface $ftm */
    $ftm = \Drupal::service('plugin.manager.field.field_type');

    $storages = $efm->getFieldStorageDefinitions($this->entityTypeId);

    if ([] === $storages) {
      return [];
    }

    /**
     * @var \Drupal\Core\Field\FieldStorageDefinitionInterface[][] $storagesByType
     *   Format: $[$fieldType][$fieldName] = $fieldStorageDefinition
     */
    $storagesByType = [];
    foreach ($storages as $fieldName => $storage) {
      $storagesByType[$storage->getType()][$fieldName] = $storage;
    }

    /**
     * @var string[] $fieldLabels
     *   Format: $[$fieldName] = $fieldLabel
     * @var true[] $fieldLabelsMissing
     *   Format: $[$fieldName] = true
     * @var string[][] $groupedOptionsPre
     *   Format: $[$targetTypeLabel][$fieldName] = $fieldTypeLabel
     */
    $fieldLabels = [];
    $fieldLabelsMissing = [];
    $groupedOptionsPre = [];
    foreach ($storagesByType as $fieldTypeId => $storagesForType) {

      try {
        $fieldTypeDefinition = $ftm->getDefinition($fieldTypeId, false);
      }
      catch (PluginNotFoundException $e) {
        throw new \RuntimeException('Misbehaving FieldTypeManager::getDefinition(): Exception thrown, even though $exception_on_invalid is false.', 0, $e);
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

      $fieldTypeLabel = $fieldTypeDefinition['label'] ?? $fieldTypeId;

      foreach ($storagesForType as $fieldName => $storage) {

        if (NULL === $targetTypeId = $storage->getSetting('target_type')) {
          continue;
        }

        if (NULL !== $this->targetTypeId){
          if ($this->targetTypeId !== $targetTypeId) {
            continue;
          }

          $targetTypeLabel = '';
        }
        else {
          if (!isset($entityTypeLabels[$targetTypeId])) {
            continue;
          }

          $targetTypeLabel = (string)$entityTypeLabels[$targetTypeId];
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

        $groupedOptionsPre[$targetTypeLabel][$fieldName] = $fieldTypeLabel;
      }
    }

    $moreLabels = $this->fieldNamesGetLabels($fieldLabelsMissing);

    $fieldLabels = array_replace($fieldLabels, $moreLabels);

    $groupedOptions = [];
    foreach ($groupedOptionsPre as $targetTypeLabel => $targetTypeFields) {
      foreach ($targetTypeFields as $fieldName => $fieldTypeLabel) {

        $fieldLabel = $fieldLabels[$fieldName] ?? $fieldName;

        $groupedOptions[$targetTypeLabel][$fieldName] = $fieldLabel . ' (' . $fieldTypeLabel . ')';
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
     *   Format: $[fieldName]['bundles'][] = $bundleName
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

    $labels = [];
    foreach ($labelAliases as $fieldName => $fieldLabelAliases) {
      $labels[$fieldName] = implode(' | ', $fieldLabelAliases);
    }

    return $labels;
  }
}
