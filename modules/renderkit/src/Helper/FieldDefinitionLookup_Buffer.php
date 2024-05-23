<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Drupal\Core\Field\FieldDefinitionInterface;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;

#[Service]
class FieldDefinitionLookup_Buffer implements FieldDefinitionLookupInterface {

  /**
   * @var \Drupal\Core\Field\FieldDefinitionInterface[][]
   */
  private array $definitions = [];

  /**
   * @var true[][]
   */
  private array $fieldProcessed = [];

  /**
   * @var true[]
   */
  private array $etProcessed = [];

  /**
   * @param \Drupal\renderkit\Helper\FieldDefinitionLookupInterface $decorated
   */
  public function __construct(
    #[GetService(FieldDefinitionLookup::class)]
    private readonly FieldDefinitionLookupInterface $decorated,
  ) {}

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
