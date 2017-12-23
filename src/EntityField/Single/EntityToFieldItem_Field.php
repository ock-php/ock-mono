<?php

namespace Drupal\renderkit8\EntityField\Single;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValue_CallbackMono;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\renderkit8\Schema\CfSchema_EtAndFieldName;

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function schema(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundle = NULL
  ) {

    return CfSchema_ValueToValue_CallbackMono::fromStaticMethod(
      __CLASS__,
      'create',
      CfSchema_EtAndFieldName::create(
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
   * @return \Drupal\Core\Field\FieldItemInterface|null
   */
  public function entityGetItem(FieldableEntityInterface $entity) {

    if ($this->entityTypeId !== $entity->getEntityTypeId()) {
      return NULL;
    }

    $item = $entity->get($this->fieldName)->first();

    if (!$item instanceof FieldItemInterface) {
      return NULL;
    }

    return $item;
  }
}
