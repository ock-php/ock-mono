<?php

namespace Drupal\renderkit\EntityToRelatedIds;

class EntityReferenceField extends EntityToRelatedIdsBase {

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
  public static function createFromFieldName($fieldName) {
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
  public function __construct($fieldName, $targetType) {
    $this->fieldName = $fieldName;
    $this->targetType = $targetType;
  }

  /**
   * @return string
   */
  public function getTargetType() {
    return $this->targetType;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  public function entityGetRelatedIds($entityType, $entity) {
    $relatedIds = [];
    foreach (field_get_items($entityType, $entity, $this->fieldName) ?: [] as $itemDelta => $item) {
      if (!empty($item['target_id'])) {
        $relatedIds[$itemDelta] = $item['target_id'];
      }
    }
    return $relatedIds;
  }
}
