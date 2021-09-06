<?php
declare(strict_types=1);

namespace Drupal\renderkit\Helper;

interface FieldDefinitionLookupInterface {

  /**
   * @param string $entityType
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  public function etGetFieldDefinitions($entityType);

  /**
   * @param string $entityType
   * @param string $fieldName
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface|null
   */
  public function etAndFieldNameGetDefinition($entityType, $fieldName);
}
