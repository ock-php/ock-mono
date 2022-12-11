<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\ock\Attribute\DI\RegisterService;

class FieldDefinitionLookup implements FieldDefinitionLookupInterface {

  const SERVICE_ID = 'renderkit.field_definition_lookup';

  /**
   * @return \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  #[RegisterService(self::SERVICE_ID)]
  public static function createBuffered(): FieldDefinitionLookupInterface {
    return new FieldDefinitionLookup_Buffer(
      self::createBufferless(),
    );
  }

  /**
   * @return \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  public static function createBufferless(): FieldDefinitionLookupInterface {

    return new self(
      \Drupal::service('entity_field.manager'));
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  public function __construct(
    private readonly EntityFieldManagerInterface $entityFieldManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function etGetFieldDefinitions(string $entity_type): array {

    try {
      $storageDefinitions = $this->entityFieldManager
        ->getFieldStorageDefinitions($entity_type);
    }
    catch (\LogicException $e) {
      throw $e;
      // It seems this et is not fieldable.
      # return [];
    }

    $definitions = [];
    foreach ($storageDefinitions as $fieldName => $storageDefinition) {
      $definitions[$fieldName] = BaseFieldDefinition::createFromFieldStorageDefinition(
        $storageDefinition);
    }

    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function etAndFieldNameGetDefinition(string $entity_type, string $field_name): ?FieldDefinitionInterface {

    try {
      $storageDefinitions = $this->entityFieldManager
        ->getFieldStorageDefinitions($entity_type);
    }
    catch (\LogicException $e) {
      throw $e;
      // It seems this et is not fieldable.
      # return NULL;
    }

    if (!isset($storageDefinitions[$field_name])) {
      return NULL;
    }

    $storageDefinition = $storageDefinitions[$field_name];

    return BaseFieldDefinition::createFromFieldStorageDefinition($storageDefinition);
  }
}
