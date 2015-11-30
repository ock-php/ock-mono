<?php

namespace Drupal\renderkit\Field;

class Field implements FieldInterface {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @param string $fieldName
   */
  function __construct($fieldName) {
    $this->fieldName = $fieldName;
  }

  /**
   * @param object $entity
   *
   * @return array[][]
   *   Format: $[$langcode][$delta] = $item
   */
  function entityGetAllItems($entity) {
    $key = $this->fieldName;
    return empty($entity->$key)
      ? $entity->$key
      : array();
  }

  /**
   * @param object $entity
   * @param string $langcode
   *
   * @return array[][]
   *   Format: $[$delta] = $item
   */
  function entityLangGetItems($entity, $langcode = LANGUAGE_NONE) {
    $key = $this->fieldName;
    if (empty($entity->$key)) {
      return array();
    }
    $itemsAll = $entity->$key;
    if (!empty($itemsAll[$langcode])) {
      return $itemsAll[$langcode];
    }
    if ($langcode !== LANGUAGE_NONE && !empty($itemsAll[LANGUAGE_NONE])) {
      return $itemsAll[LANGUAGE_NONE];
    }
    return array();
  }
}
