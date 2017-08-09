<?php

namespace Drupal\renderkit8\EntityCondition;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit8\Schema\CfSchema_FieldName;

/**
 * An entity condition that returns true if a given timestamp is contained in
 * one of the date intervals in a given date field on the entity.
 */
class EntityCondition_DateFieldInterval implements EntityConditionInterface {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string|int
   */
  private $referenceTimestamp;

  /**
   * @CfrPlugin(
   *   id = "dateFieldInterval",
   *   label = @t("Date field interval")
   * )
   *
   * @param string $entityType
   * @param string $bundleName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema($entityType = NULL, $bundleName = NULL) {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'createNow',
      [
        new CfSchema_FieldName(
          ['date'],
          $entityType,
          $bundleName)
      ],
      [t('Date field')]);
  }

  /**
   * @param string $fieldName
   *
   * @return \Drupal\renderkit8\EntityCondition\EntityCondition_DateFieldInterval
   */
  public static function createNow($fieldName) {
    return new self($fieldName, time());
  }

  /**
   * @param string $fieldName
   * @param string|int $referenceTimestamp
   */
  public function __construct($fieldName, $referenceTimestamp) {
    $this->fieldName = $fieldName;
    $this->referenceTimestamp = $referenceTimestamp;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  public function entityCheckCondition($entityType, $entity) {
    foreach (field_get_items($entityType, $entity, $this->fieldName) ?: [] as $item) {
      if (!isset($item['value']) || $item['value'] <= $this->referenceTimestamp) {
        if (!isset($item['value2']) || $this->referenceTimestamp < $item['value2']) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }
}
