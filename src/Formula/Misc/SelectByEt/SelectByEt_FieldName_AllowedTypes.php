<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula\Misc\SelectByEt;

class SelectByEt_FieldName_AllowedTypes extends SelectByEt_FieldName_Base {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @param string[]|null $allowedFieldTypes
   */
  public function __construct(array $allowedFieldTypes = NULL) {
    $this->allowedFieldTypes = $allowedFieldTypes;
  }

  /**
   * @return string
   */
  public function getCacheId(): string {
    return static::class . ':' . implode(',', $this->allowedFieldTypes);
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

    $storagesByType = parent::getStorageDefinitionsByType(
      $entityTypeId,
      $bundleNames);

    if (NULL === $this->allowedFieldTypes) {
      return $storagesByType;
    }

    return array_intersect_key(
      $storagesByType,
      array_fill_keys($this->allowedFieldTypes, TRUE));
  }

}
