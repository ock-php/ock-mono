<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay;

use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;

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

    try {
      $item = $fieldItemList->first();
    }
    catch (MissingDataException $e) {
      // No reason to log this, it just means the item does not exist.
      unset($e);
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
