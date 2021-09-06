<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Multi;

use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\renderkit\Formula\Formula_EtAndFieldName;

class EntityToFieldItemList_Field implements EntityToFieldItemListInterface {

  /**
   * @var string
   */
  private $entityTypeId;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @CfrPlugin("field", "Field", inline = true)
   *
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function formula(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundle = NULL
  ) {

    return Formula_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'create',
      Formula_EtAndFieldName::create(
        $allowedFieldTypes,
        $entityType,
        $bundle));
  }

  /**
   * @param array $settings
   *
   * @return self
   */
  public static function create(array $settings) {
    return new self(
      $settings['entity_type'],
      $settings['field_name']);
  }

  /**
   * @param string $entityTypeId
   * @param string $fieldName
   */
  public function __construct($entityTypeId, $fieldName) {
    $this->entityTypeId = $entityTypeId;
    $this->fieldName = $fieldName;
  }

  /**
   * @param \Drupal\Core\Entity\FieldableEntityInterface $entity
   *
   * @return \Drupal\Core\Field\FieldItemListInterface|null
   */
  public function entityGetItemList(FieldableEntityInterface $entity) {

    if ($this->entityTypeId !== $entity->getEntityTypeId()) {
      return NULL;
    }

    return $entity->get($this->fieldName);
  }
}
