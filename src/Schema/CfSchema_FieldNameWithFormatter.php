<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaBase;

class CfSchema_FieldNameWithFormatter extends CfSchema_Drilldown_OptionsSchemaBase {

  /**
   * @param string $entityType
   * @param string $bundleName
   */
  public function __construct($entityType = NULL, $bundleName = NULL) {

    parent::__construct(
      new CfSchema_FieldName(
        NULL,
        $entityType,
        $bundleName));
  }

  /**
   * @param string|int $fieldName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($fieldName) {

    $fieldInfo = field_info_field($fieldName);

    if (!isset($fieldInfo['type'])) {
      return NULL;
    }

    return new CfSchema_FieldFormatter(
      $fieldName,
      $fieldInfo['type']);
  }

  /**
   * @return string
   */
  public function getIdKey() {
    return 'field';
  }

  /**
   * @return string
   */
  public function getOptionsKey() {
    return 'display';
  }
}
