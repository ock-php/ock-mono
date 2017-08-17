<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Entity display handler to view a specific field on all the entities.
 */
abstract class EntityDisplay_FieldItemsBase extends EntityDisplayBase {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @param string $entity_type
   * @param string $field_name
   */
  public function __construct($entity_type, $field_name) {
    $this->entityType = $entity_type;
    $this->fieldName = $field_name;
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  final public function buildEntity(EntityInterface $entity) {

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
  abstract protected function buildFieldItems(FieldItemListInterface $fieldItemList);

}
