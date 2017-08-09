<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Drupal\renderkit\Util\FieldUtil;

class CfSchema_FieldName implements CfSchema_OptionsInterface {

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
  public function __construct(array $allowedFieldTypes = NULL, $entityType = NULL, $bundleName = NULL) {
    $this->fieldTypes = $allowedFieldTypes;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {
    return FieldUtil::fieldnameEtBundleExists(
      $id,
      $this->entityType,
      $this->bundleName);
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {
    return FieldUtil::fieldTypesGetFieldNameGroupedOptions(
      $this->fieldTypes,
      $this->entityType,
      $this->bundleName);
  }

  /**
   * @param string|mixed $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {
    return FieldUtil::fieldnameEtBundleGetLabel(
      $id,
      $this->entityType,
      $this->bundleName);
  }
}
