<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

class Formula_EtDotFieldName_EntityReference extends Formula_EtDotFieldName_ProxyCacheBase {

  /**
   * @param string|null $entityTypeId
   * @param string|null $bundleName
   * @param string|null $targetTypeId
   */
  public function __construct(
    $entityTypeId = NULL,
    $bundleName = NULL,
    private $targetTypeId = NULL
  ) {
    $extraCacheId = 'entity_reference';
    if (NULL !== $targetTypeId) {
      $extraCacheId .= ':' . $targetTypeId;
    }
    parent::__construct(
      $entityTypeId,
      $bundleName,
      $extraCacheId,
    );
  }

  /**
   * @param string $entityTypeId
   * @param string|null $bundleName
   *
   * @return string[][]
   *   Format: $[$groupLabel][$fieldName] = $fieldLabel
   */
  protected function etGetGroupedOptions(string $entityTypeId, string $bundleName = NULL): array {
    $formula = new Formula_FieldName_EntityReference(
      $entityTypeId,
      $bundleName,
      $this->targetTypeId,
    );
    return $formula->getData();
  }
}

