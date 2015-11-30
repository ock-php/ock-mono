<?php

namespace Drupal\renderkit\EntityCondition;

/**
 * An entity condition that returns true if a given timestamp is contained in
 * one of the date intervals in a given date field on the entity.
 */
class DateFieldIntervalEntityCondition implements EntityConditionInterface {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string|int
   */
  private $referenceTimestamp;

  /**
   * @param string $fieldName
   *
   * @return \Drupal\renderkit\EntityCondition\DateFieldIntervalEntityCondition
   */
  static function createNow($fieldName) {
    return new self($fieldName, time());
  }

  /**
   * @param string $entityType
   *   The entity type. Contextual argument.
   * @param string $bundleName
   *   The bundle name. Contextual argument.
   */
  static function createPlugin($entityType = NULL, $bundleName = NULL) {
    // @todo Create plugin based on field name value handler.
  }

  /**
   * @param string $fieldName
   * @param string|int $referenceTimestamp
   */
  function __construct($fieldName, $referenceTimestamp) {
    $this->fieldName = $fieldName;
    $this->referenceTimestamp = $referenceTimestamp;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return bool
   */
  function entityCheckCondition($entityType, $entity) {
    foreach (field_get_items($entityType, $entity, $this->fieldName) ?: array() as $item) {
      if (!isset($item['value']) || $item['value'] <= $this->referenceTimestamp) {
        if (!isset($item['value2']) || $this->referenceTimestamp < $item['value2']) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }
}
