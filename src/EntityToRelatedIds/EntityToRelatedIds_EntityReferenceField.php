<?php

namespace Drupal\renderkit8\EntityToRelatedIds;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\renderkit8\EntityField\Multi\EntityToFieldItemListInterface;

class EntityToRelatedIds_EntityReferenceField extends EntityToRelatedIdsBase {

  /**
   * @var \Drupal\renderkit8\EntityField\Multi\EntityToFieldItemListInterface
   */
  private $field;

  /**
   * @var string
   */
  private $targetType;

  /**
   * @param \Drupal\renderkit8\EntityField\Multi\EntityToFieldItemListInterface $field
   * @param string $targetType
   */
  public function __construct(EntityToFieldItemListInterface $field, $targetType) {
    $this->field = $field;
    $this->targetType = $targetType;
  }

  /**
   * @return string
   */
  public function getTargetType() {
    return $this->targetType;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  public function entityGetRelatedIds(EntityInterface $entity) {

    if (!$entity instanceof FieldableEntityInterface) {
      return [];
    }

    if (NULL === $items = $this->field->entityGetItemList($entity)) {
      return [];
    }

    $relatedIds = [];
    foreach ($items as $itemDelta => $item) {
      if ($item instanceof EntityReferenceItem) {
        // @todo Validate target type?
        $relatedIds[$itemDelta] = $item->target_id;
      }
    }

    return $relatedIds;
  }
}
