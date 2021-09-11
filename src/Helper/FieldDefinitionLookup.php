<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldDefinitionInterface;

class FieldDefinitionLookup implements FieldDefinitionLookupInterface {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private $entityFieldManager;

  /**
   * @return \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  public static function createBuffered() {
    return new FieldDefinitionLookup_Buffer(
      self::createBufferless());
  }

  /**
   * @return \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  public static function createBufferless() {

    return new self(
      \Drupal::service('entity_field.manager'));
  }

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   */
  public function __construct(EntityFieldManagerInterface $entityFieldManager) {
    $this->entityFieldManager = $entityFieldManager;
  }

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
