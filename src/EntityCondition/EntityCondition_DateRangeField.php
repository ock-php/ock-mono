<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal_Callback;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\datetime_range\Plugin\Field\FieldType\DateRangeItem;
use Drupal\renderkit\EntityField\Multi\EntityToFieldItemList;
use Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface;

/**
 * An entity condition that returns true if a given timestamp is contained in
 * one of the date intervals in a given date field on the entity.
 */
class EntityCondition_DateRangeField implements EntityConditionInterface {

  /**
   * @var \Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface
   */
  private $field;

  /**
   * @var int|string
   */
  private $referenceTimestamp;

  /**
   * @CfrPlugin("dateRangeField", "Date range field")
   *
   * @param string $entityType
   * @param string $bundleName
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula($entityType = NULL, $bundleName = NULL): FormulaInterface {

    return Formula_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'createNow',
      [
        EntityToFieldItemList::formula(
          ['daterange'],
          $entityType,
          $bundleName),
      ],
      [t('Date field')]);
  }

  /**
   * @param \Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface $field
   *
   * @return self
   */
  public static function createNow(EntityToFieldItemListInterface $field): self {
    return new self($field, time());
  }

  /**
   * @param \Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface $field
   * @param string|int $referenceTimestamp
   */
  public function __construct(
    EntityToFieldItemListInterface $field,
    $referenceTimestamp
  ) {
    $this->field = $field;
    $this->referenceTimestamp = $referenceTimestamp;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return bool
   */
  public function entityCheckCondition(EntityInterface $entity): bool {

    if (!$entity instanceof FieldableEntityInterface) {
      return FALSE;
    }

    if (NULL === $items = $this->field->entityGetItemList($entity)) {
      return FALSE;
    }

    foreach ($items as $item) {

      if ($item instanceof DateRangeItem) {
        if ($this->itemCheckCondition($item)) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * @param \Drupal\datetime_range\Plugin\Field\FieldType\DateRangeItem $item
   *
   * @return bool
   */
  private function itemCheckCondition(DateRangeItem $item): bool {

    // @todo Convert to timestamp?
    if (NULL !== $item->value && $item->value > $this->referenceTimestamp) {
      return FALSE;
    }

    if (NULL !== $item->end_value && $item->end_value > $this->referenceTimestamp) {
      return FALSE;
    }

    return TRUE;
  }
}
