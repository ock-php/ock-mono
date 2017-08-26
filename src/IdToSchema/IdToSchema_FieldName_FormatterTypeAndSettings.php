<?php

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Drupal\renderkit8\Helper\FieldDefinitionLookup;

class IdToSchema_FieldName_FormatterTypeAndSettings implements IdToSchemaInterface {

  /**
   * @var \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @var string
   */
  private $entityType;

  /**
   * @param string $entityType
   */
  public function __construct($entityType) {
    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();
    $this->entityType = $entityType;
  }

  /**
   * @param string|int $fieldName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($fieldName) {

    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $this->entityType,
      $fieldName);

    if (NULL === $fieldDefinition) {
      return NULL;
    }

    $schema = IdToSchema_FormatterTypeName_FormatterSettings::createDrilldownSchema(
      \Drupal::service('plugin.manager.field.formatter'),
      $fieldDefinition);

    return new CfSchema_Label($schema, t('Formatter'));
  }
}
