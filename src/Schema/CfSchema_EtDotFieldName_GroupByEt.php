<?php
declare(strict_types=1);

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;

/**
 * Schema where the value is like 'node.body', and everything is in one select element.
 */
class CfSchema_EtDotFieldName_GroupByEt implements CfSchema_SelectInterface {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private $entityFieldManager;

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   *   Contextual parameter.
   * @param string $bundleName
   *   Contextual parameter.
   *
   * @return self
   */
  public static function create(array $allowedFieldTypes = NULL, $entityType = NULL, $bundleName = NULL): self {

    // @todo Real dependency injection.

    /** @var \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager */
    $entityFieldManager = \Drupal::service('entity_field.manager');

    return new self(
      $entityFieldManager,
      $allowedFieldTypes,
      $entityType,
      $bundleName);

  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param null|string[] $allowedFieldTypes
   * @param string|null $entityType
   *   Contextual parameter.
   * @param string|null $bundleName
   *   Contextual parameter.
   */
  public function __construct(
    EntityFieldManagerInterface $entityFieldManager,
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundleName = NULL
  ) {
    $this->entityFieldManager = $entityFieldManager;
    $this->allowedFieldTypes = $allowedFieldTypes;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions(): array {

    $options = [];
    foreach ($this->getFieldsGrouped() as $field_type => $fields_by_et) {
      // @todo Human-readable field type label!
      $type_label = $field_type;
      foreach ($fields_by_et as $et => $fields) {
        // @todo Human-readable entity type label!
        $et_label = $et;
        foreach ($fields as $field_name => $bundles) {
          // @todo Human-readable field label from field instances!
          $field_label = $field_name;
          $options[$et_label][$et . '.' . $field_name] = $type_label . ': ' . $field_label;
        }
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($etAndFieldName) {
    list($et, $fieldName) = explode('.', $etAndFieldName . '.');

    if (NULL !== $this->entityType) {
      if ($et !== $this->entityType) {
        return NULL;
      }
    }

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    if (!isset($map[$et][$fieldName])) {
      return NULL;
    }

    $fieldInfo = $map[$et][$fieldName];

    if (NULL !== $this->bundleName) {
      if (!isset($fieldInfo['bundles'][$this->bundleName])) {
        return NULL;
      }
    }

    if (NULL !== $this->allowedFieldTypes) {
      if (!\in_array($fieldInfo['type'], $this->allowedFieldTypes, TRUE)) {
        return NULL;
      }
    }

    // @todo Look up real field name.
    return $etAndFieldName;
  }

  /**
   * @param string $etAndFieldName
   *   Format: $entityType . '.' . $fieldName
   *
   * @return bool
   */
  public function idIsKnown($etAndFieldName): bool {
    list($et, $fieldName) = explode('.', $etAndFieldName . '.');

    if (NULL !== $this->entityType) {
      if ($et !== $this->entityType) {
        return FALSE;
      }
    }

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    if (!isset($map[$et][$fieldName])) {
      return FALSE;
    }

    $fieldInfo = $map[$et][$fieldName];

    if (NULL !== $this->bundleName) {
      if (!isset($fieldInfo['bundles'][$this->bundleName])) {
        return FALSE;
      }
    }

    if (NULL !== $this->allowedFieldTypes) {
      if (!\in_array($fieldInfo['type'], $this->allowedFieldTypes, TRUE)) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * @return string[][][][]
   *   Format: $[$field_type][$entity_type][$field_name][$bundle_name] = $bundle_name
   */
  private function getFieldsGrouped(): array {

    /**
     * @var array[][] $map
     *   Format: $[$entity_type][$field_name] = ['type' => $field_type, 'bundles' => $bundles]
     */
    $map = $this->entityFieldManager->getFieldMap();

    if (NULL !== $this->entityType) {
      if (!isset($map[$this->entityType])) {
        return [];
      }

      $map = [$this->entityType => $map[$this->entityType]];
    }

    if (NULL !== $this->bundleName) {

      $filteredMap = [];
      foreach ($map as $entityTypeId => $fields) {
        foreach ($fields as $fieldName => $fieldInfo) {
          if (isset($fieldInfo['bundles'][$this->bundleName])) {
            $filteredMap[$entityTypeId][$fieldName] = $fieldInfo;
          }
        }
      }
      $map = $filteredMap;
    }

    /**
     * @var string[][][][] $grouped
     *   Format: $[$field_type][$entity_type][$field_name][$bundle_name] = $bundle_name
     */
    $grouped = [];
    if (NULL !== $this->allowedFieldTypes) {
      $allowedTypesMap = array_fill_keys($this->allowedFieldTypes, TRUE);

      foreach ($map as $entityTypeId => $fields) {
        foreach ($fields as $fieldName => $fieldInfo) {
          $type = $fieldInfo['type'];
          if (isset($allowedTypesMap[$type])) {
            $grouped[$type][$entityTypeId][$fieldName] = $fieldInfo['bundles'];
          }
        }
      }
    }
    else {
      foreach ($map as $entityTypeId => $fields) {
        foreach ($fields as $fieldName => $fieldInfo) {
          $type = $fieldInfo['type'];
          $grouped[$type][$entityTypeId][$fieldName] = $fieldInfo['bundles'];
        }
      }
    }

    return $grouped;
  }
}
