<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToRelatedIds;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface;

class EntityToRelatedIds_EntityReferenceField extends EntityToRelatedIdsBase {

  /**
   * @param \Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface $field
   * @param string $targetType
   */
  public function __construct(
    private readonly EntityToFieldItemListInterface $field,
    private readonly string $targetType,
  ) {}

  /**
   * @return string
   */
  public function getTargetType(): string {
    return $this->targetType;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return int[]
   *   Format: $[] = $relatedEntityId
   */
  public function entityGetRelatedIds(EntityInterface $entity): array {

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
