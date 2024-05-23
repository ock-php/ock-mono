<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Entity display handler to view a specific field on all the entities.
 */
abstract class EntityDisplay_FieldItemsBase extends EntityDisplayBase {

  /**
   * @param string $entityType
   * @param string $fieldName
   */
  public function __construct(
    private readonly string $entityType,
    private readonly string $fieldName,
  ) {}

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  final public function buildEntity(EntityInterface $entity): array {

    if (!$entity instanceof FieldableEntityInterface) {
      return [];
    }

    if ($this->entityType !== $entity->getEntityTypeId()) {
      return [];
    }

    $fieldItemList = $entity->get($this->fieldName);

    return $this->buildFieldItems($fieldItemList);
  }

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $fieldItemList
   *
   * @return array
   */
  abstract protected function buildFieldItems(FieldItemListInterface $fieldItemList): array;

}
