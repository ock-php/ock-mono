<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityCondition;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\datetime_range\Plugin\Field\FieldType\DateRangeItem;
use Drupal\renderkit\EntityField\Multi\EntityToFieldItemListInterface;
use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Ock\Ock\Text\Text;

/**
 * An entity condition that returns true if a given timestamp is contained in
 * one of the date intervals in a given date field on the entity.
 */
class EntityCondition_DateRangeField implements EntityConditionInterface {

  /**
   * @CfrPlugin("dateRangeField", "Date range field")
   *
   * @param string|null $entityType
   * @param string|null $bundleName
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(string $entityType = NULL, string $bundleName = NULL): FormulaInterface {
    return Formula::group()
      ->add(
        'field',
        Text::t('Date range field'),
        // @todo Inject field type 'daterange' as context.
        // @todo Or just pick a field name.
        Formula::iface(EntityToFieldItemListInterface::class),
      )
      ->call([self::class, 'createNow'], ['field']);
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
   * @param int|string $referenceTimestamp
   */
  public function __construct(
    private readonly EntityToFieldItemListInterface $field,
    private readonly int|string $referenceTimestamp
  ) {}

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
