<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldDefinitionInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(public: true)]
class FieldDefinitionLookup implements FieldDefinitionLookupInterface {

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

    $storageDefinitions = $this->entityFieldManager
      ->getFieldStorageDefinitions($entity_type);
    try {
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
