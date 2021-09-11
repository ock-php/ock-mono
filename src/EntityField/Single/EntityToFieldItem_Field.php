<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Single;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\ValueToValue\Formula_ValueToValue_CallbackMono;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\renderkit\Formula\Formula_EtAndFieldName;

class EntityToFieldItem_Field implements EntityToFieldItemInterface {

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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundle = NULL
  ): FormulaInterface {

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
  public static function create(array $settings): self {
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
   * @return \Drupal\Core\Field\FieldItemInterface|null
   */
  public function entityGetItem(FieldableEntityInterface $entity): ?FieldItemInterface {

    if ($this->entityTypeId !== $entity->getEntityTypeId()) {
      return NULL;
    }

    try {
      $item = $entity->get($this->fieldName)->first();
    }
    catch (MissingDataException $e) {
      unset($e);
      return null;
    }

    if (!$item instanceof FieldItemInterface) {
      return NULL;
    }

    return $item;
  }
}
