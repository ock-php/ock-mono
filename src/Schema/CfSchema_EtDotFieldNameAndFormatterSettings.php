<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaBase;
use Drupal\renderkit8\Helper\FieldDefinitionLookup;

class CfSchema_EtDotFieldNameAndFormatterSettings extends CfSchema_Drilldown_OptionsSchemaBase {

  /**
   * @var \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @param string $entityType
   * @param string $bundleName
   */
  public function __construct($entityType = NULL, $bundleName = NULL) {

    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();

    parent::__construct(
      CfSchema_EtDotFieldName::create(
        NULL,
        $entityType,
        $bundleName));
  }

  /**
   * @param string|int $etAndFieldName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($etAndFieldName) {
    list($et, $fieldName) = explode('.', $etAndFieldName . '.');

    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $et,
      $fieldName);

    if (NULL === $fieldDefinition) {
      return NULL;
    }

    return CfSchema_FieldFormatterTypeAndSettings::create($fieldDefinition);
  }

  /**
   * @return string
   */
  public function getIdKey() {
    return 'field';
  }

  /**
   * @return string
   */
  public function getOptionsKey() {
    return 'display';
  }
}
