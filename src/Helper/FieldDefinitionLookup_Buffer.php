<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Drupal\Core\Field\FieldDefinitionInterface;

class FieldDefinitionLookup_Buffer implements FieldDefinitionLookupInterface {

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface[][]
   */
  private $definitions = [];

  /**
   * @var true[][]
   */
  private $fieldProcessed = [];

  /**
   * @var true[]
   */
  private $etProcessed = [];

  /**
   * @var \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  private $decorated;

  /**
   * @param \Drupal\renderkit\Helper\FieldDefinitionLookupInterface $decorated
   */
  public function __construct(FieldDefinitionLookupInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * {@inheritdoc}
   */
  public function etGetFieldDefinitions(string $entity_type): array {

    if (isset($this->etProcessed[$entity_type])) {
      return $this->definitions[$entity_type];
    }

    $this->etProcessed[$entity_type] = TRUE;

    return $this->definitions[$entity_type] = $this->decorated->etGetFieldDefinitions($entity_type);
  }

  /**
   * {@inheritdoc}
   */
  public function etAndFieldNameGetDefinition(string $entity_type, string $field_name): ?FieldDefinitionInterface {

    if (isset($this->fieldProcessed[$entity_type][$field_name])) {
      return $this->definitions[$entity_type][$field_name];
    }

    $this->fieldProcessed[$entity_type][$field_name] = TRUE;

    if (isset($this->etProcessed[$entity_type])) {
      return NULL;
    }

    return $this->definitions[$entity_type][$field_name] = $this->decorated->etAndFieldNameGetDefinition(
      $entity_type,
      $field_name);
  }
}
