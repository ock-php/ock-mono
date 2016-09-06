<?php

namespace Drupal\renderkit\EnumMap;

use Drupal\renderkit\Util\FieldUtil;
use Drupal\cfrapi\EnumMap\EnumMapInterface;

class EnumMap_FieldName implements EnumMapInterface {

  /**
   * @var null|string[]
   */
  private $fieldTypes;

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
   */
  public function __construct($allowedFieldTypes = NULL, $entityType = NULL, $bundleName = NULL) {
    $this->fieldTypes = $allowedFieldTypes;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @return mixed[]
   */
  public function getSelectOptions() {
    return FieldUtil::fieldTypesGetFieldNameOptions($this->fieldTypes, $this->entityType, $this->bundleName);
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return FieldUtil::fieldnameEtBundleGetLabel($id, $this->entityType, $this->bundleName);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return FieldUtil::fieldnameEtBundleExists($id, $this->entityType, $this->bundleName);
  }
}
