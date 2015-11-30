<?php

namespace Drupal\renderkit\EntityToEntities;

class MultiEntityReferenceField extends EntityToEntitiesMultipleBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string
   */
  private $targetType;

  /**
   * @param string $fieldName
   *
   * @return static|null
   */
  static function createFromFieldName($fieldName) {
    $fieldInfo = field_info_field($fieldName);
    if (!$fieldInfo ) {
      return NULL;
    }
    // @todo Check field type and target type.
    // @todo Return broken dummy relation if something wrong.
    return new static($fieldName, $fieldInfo['target_type']);
  }

  /**
   * @param string $fieldName
   * @param string $targetType
   */
  function __construct($fieldName, $targetType) {
    $this->fieldName = $fieldName;
    $this->targetType = $targetType;
  }

  /**
   * @return string
   */
  function getTargetEntityType() {
    return $this->targetType;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[][]
   */
  function entitiesGetRelated($entityType, array $entities) {
    // TODO: Implement entitiesGetRelated() method.
  }
}
