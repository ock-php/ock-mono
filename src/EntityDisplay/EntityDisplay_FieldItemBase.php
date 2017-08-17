<?php

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Entity display handler to view a specific field on all the entities.
 */
abstract class EntityDisplay_FieldItemBase extends EntityDisplay_FieldItemsBase {

  /**
   * @param \Drupal\Core\Field\FieldItemListInterface $fieldItemList
   *
   * @return array
   */
  final protected function buildFieldItems(FieldItemListInterface $fieldItemList) {

    if (NULL === $item = $fieldItemList->first()) {
      return [];
    }

    // $item is documented as TypedDataInterface, not FieldItemInterface..
    if (!$item instanceof FieldItemInterface) {
      return [];
    }

    return $this->buildFieldItem($item);
  }

  /**
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *
   * @return array
   */
  abstract protected function buildFieldItem(FieldItemInterface $item);

}
