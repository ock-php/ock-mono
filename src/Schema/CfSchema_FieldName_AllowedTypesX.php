<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\IdToSchema\IdToSchema_Callback;
use Donquixote\Cf\IdToSchema\IdToSchema_Fixed;
use Donquixote\Cf\Schema\Proxy\Cache\CfSchema_Proxy_Cache_SelectBase;

/**
 * Schema where the value is like 'body' for field 'node.body'.
 */
class CfSchema_FieldName_AllowedTypesX extends CfSchema_Proxy_Cache_SelectBase {

  /**
   * @var string[]|null
   */
  private $allowedFieldTypes;

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param array $allowedFieldTypes
   * @param string|null $entityTypeId
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  public static function createEtToSchema(
    array $allowedFieldTypes,
    $entityTypeId = NULL,
    $bundle = NULL
  ) {

    if (NULL === $entityTypeId) {
      return new IdToSchema_Callback(
        function($selectedEntityTypeId) use ($allowedFieldTypes) {
          return new self($allowedFieldTypes, $selectedEntityTypeId);
        });
    }

    // Only allow this one entity type.
    return new IdToSchema_Fixed(
      [
        $entityTypeId => new self(
          $allowedFieldTypes,
          $entityTypeId,
          $bundle)
      ]);
  }

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return self
   */
  public static function create(
    array $allowedFieldTypes = NULL,
    $entityType,
    $bundleName = NULL
  ) {

    // @todo Real dependency injection.

    return new self(
      $allowedFieldTypes,
      $entityType,
      $bundleName);

  }

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   * @param string|null $bundleName
   */
  public function __construct(
    array $allowedFieldTypes = NULL,
    $entityType,
    $bundleName = NULL
  ) {
    $this->allowedFieldTypes = $allowedFieldTypes;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;

    $signatureData = [
      $allowedFieldTypes,
      $entityType,
      $bundleName,
    ];

    $cacheId = 'renderkit:schema:field_name:allowed_types:'
      . sha1(serialize($signatureData));

    parent::__construct($cacheId);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function getGroupedOptions() {

    $options = [];
    foreach ($this->getFieldsGrouped() as $field_type => $fields) {
      // @todo Human-readable field type label!
      $type_label = $field_type;
      foreach ($fields as $field_name => $bundles) {
        // @todo Human-readable field label from field instances!
        $field_label = $field_name;
        $options[$type_label][$field_name] = $field_label;
      }
    }

    return $options;
  }

  /**
   * @return array
   *   Format: $[$field_type][$entity_type][$field_name][$bundle_name] = $bundle_name
   */
  private function getFieldsGrouped() {

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager */
    $entityFieldManager = \Drupal::service('entity_field.manager');

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $entityFieldManager->getFieldMap();

    if (!isset($map[$this->entityType])) {
      return [];
    }

    /**
     * @var array[] $fields
     *   Format: $[$field_name] = $field_info
     */
    $fields = $map[$this->entityType];
    unset($map);

    if (NULL !== $this->bundleName) {

      $filteredFields = [];
      foreach ($fields as $fieldName => $fieldInfo) {
        if (isset($fieldInfo['bundles'][$this->bundleName])) {
          $filteredFields[$fieldName] = $fieldInfo;
        }
      }

      $fields = $filteredFields;
    }

    /**
     * @var array[][] $map
     *   Format: $[$field_type][$field_name][$bundle_name] = $bundle_name
     */
    $grouped = [];
    if (NULL !== $this->allowedFieldTypes) {
      $allowedTypesMap = array_fill_keys($this->allowedFieldTypes, TRUE);

      foreach ($fields as $fieldName => $fieldInfo) {
        $type = $fieldInfo['type'];
        if (isset($allowedTypesMap[$type])) {
          $grouped[$type][$fieldName] = $fieldInfo['bundles'];
        }
      }
    }
    else {
      foreach ($fields as $fieldName => $fieldInfo) {
        $type = $fieldInfo['type'];
        $grouped[$type][$fieldName] = $fieldInfo['bundles'];
      }
    }

    return $grouped;
  }
}
