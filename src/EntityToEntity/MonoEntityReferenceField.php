<?php

namespace Drupal\renderkit\EntityToEntity;

class MonoEntityReferenceField extends EntityToEntityBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var int
   */
  private $fieldItemDelta = 0;

  /**
   * @param string $fieldName
   */
  function __construct($fieldName) {
    $this->fieldName = $fieldName;
  }

  /**
   * @param int $fieldItemDelta
   */
  function setDelta($fieldItemDelta) {
    $this->fieldItemDelta = $fieldItemDelta;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  function entityGetRelated($entityType, $entity) {
    $items = field_get_items($entityType, $entity, $this->fieldName) ?: array();
    if (isset($items[$this->fieldItemDelta])) {
      # $item = $items[$this->fieldItemDelta];
      // @todo Load the target entity.
    }
    return NULL;
  }
}
