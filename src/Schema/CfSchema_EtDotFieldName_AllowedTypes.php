<?php

namespace Drupal\renderkit8\Schema;

class CfSchema_EtDotFieldName_AllowedTypes extends CfSchema_EtDotFieldName_ProxyCacheBase {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundleName
   * @param string[]|null $allowedFieldTypes
   */
  public function __construct(
    $entityTypeId = NULL,
    $bundleName = NULL,
    array $allowedFieldTypes = NULL
  ) {
    $this->allowedFieldTypes = $allowedFieldTypes;

    $extraCacheId = 'allowed_field_types';

    if (NULL !== $allowedFieldTypes) {
      $extraCacheId .= ':' . implode(',', $allowedFieldTypes);
    }

    parent::__construct(
      $entityTypeId,
      $bundleName,
      $extraCacheId);
  }

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   *
   * @return string[][]
   *   Format: $[$groupLabel][$fieldName] = $fieldLabel
   */
  protected function etGetGroupedOptions($entityTypeId, $bundleName = NULL) {

    $schema = new CfSchema_FieldName_AllowedTypes(
      $entityTypeId,
      $bundleName,
      $this->allowedFieldTypes);

    return $schema->getData();
  }
}

