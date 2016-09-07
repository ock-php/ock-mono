<?php

namespace Drupal\renderkit\Configurator\Id;

use Drupal\cfrapi\Configurator\Id\Configurator_SelectBase;
use Drupal\renderkit\Util\FieldUtil;

class Configurator_FieldName extends Configurator_SelectBase {

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
   *
   * @return \Drupal\renderkit\Configurator\Id\Configurator_FieldName
   */
  public static function createOptional(array $allowedFieldTypes = NULL, $entityType = NULL, $bundleName = NULL) {
    return new self($allowedFieldTypes, $entityType, $bundleName, FALSE);
  }

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   *   Contextual parameter.
   * @param string $bundleName
   *   Contextual parameter.
   * @param bool $required
   */
  public function __construct(array $allowedFieldTypes = NULL, $entityType = NULL, $bundleName = NULL, $required = TRUE) {
    $this->fieldTypes = $allowedFieldTypes;
    $this->entityType = $entityType;
    $this->bundleName = $bundleName;
    parent::__construct($required);
  }

  /**
   * @return mixed[]
   */
  protected function getSelectOptions() {
    return FieldUtil::fieldTypesGetFieldNameOptions($this->fieldTypes, $this->entityType, $this->bundleName);
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  protected function idGetLabel($id) {
    return FieldUtil::fieldnameEtBundleGetLabel($id, $this->entityType, $this->bundleName);
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  protected function idIsKnown($id) {
    return FieldUtil::fieldnameEtBundleExists($id, $this->entityType, $this->bundleName);
  }
}
