<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

use Drupal\Core\Field\FieldDefinitionInterface;

interface FieldDefinitionLookupInterface {

  /**
   * @param string $entity_type
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  public function etGetFieldDefinitions(string $entity_type): array;

  /**
   * @param string $entity_type
   * @param string $field_name
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface|null
   */
  public function etAndFieldNameGetDefinition(string $entity_type, string $field_name): ?FieldDefinitionInterface;
}
