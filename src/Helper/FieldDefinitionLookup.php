<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Helper;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;

class FieldDefinitionLookup implements FieldDefinitionLookupInterface {

  /**
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  private $entityFieldManager;

  /**
   * @return \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
   */
  public static function createBuffered() {
    return new FieldDefinitionLookup_Buffer(
      self::createBufferless());
  }

  /**
   * @return \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
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
   * @param string $et
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  public function etGetFieldDefinitions($et) {

    try {
      $storageDefinitions = $this->entityFieldManager
        ->getFieldStorageDefinitions($et);
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
   * @param string $et
   * @param string $fieldName
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface|null
   */
  public function etAndFieldNameGetDefinition($et, $fieldName) {

    try {
      $storageDefinitions = $this->entityFieldManager
        ->getFieldStorageDefinitions($et);
    }
    catch (\LogicException $e) {
      throw $e;
      // It seems this et is not fieldable.
      # return NULL;
    }

    if (!isset($storageDefinitions[$fieldName])) {
      return NULL;
    }

    $storageDefinition = $storageDefinitions[$fieldName];

    return BaseFieldDefinition::createFromFieldStorageDefinition($storageDefinition);
  }
}
