<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

class Formula_EtDotFieldName_AllowedTypes extends Formula_EtDotFieldName_ProxyCacheBase {

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

    $formula = new Formula_FieldName_AllowedTypes(
      $entityTypeId,
      $bundleName,
      $this->allowedFieldTypes);

    return $formula->getData();
  }
}

