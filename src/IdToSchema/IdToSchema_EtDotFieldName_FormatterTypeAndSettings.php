<?php
declare(strict_types=1);

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Drupal\renderkit8\Helper\FieldDefinitionLookup;
use Drupal\renderkit8\Schema\CfSchema_EtDotFieldName;

class IdToSchema_EtDotFieldName_FormatterTypeAndSettings implements IdToSchemaInterface {

  /**
   * @var \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @param string|null $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function createDrilldownSchema($entityType = NULL, $bundleName = NULL) {

    return CfSchema_Drilldown::create(
      CfSchema_EtDotFieldName::create(
        NULL,
        $entityType,
        $bundleName),
      new self($entityType, $bundleName))
      ->withKeys('field', 'display');
  }

  /**
   * @param string $entityType
   * @param string $bundleName
   */
  public function __construct($entityType = NULL, $bundleName = NULL) {

    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();
  }

  /**
   * @param string|int $etAndFieldName
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($etAndFieldName): ?CfSchemaInterface {
    list($et, $fieldName) = explode('.', $etAndFieldName . '.');

    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $et,
      $fieldName);

    if (NULL === $fieldDefinition) {
      return NULL;
    }

    return IdToSchema_FormatterTypeName_FormatterSettings::createDrilldownSchema(
      \Drupal::service('plugin.manager.field.formatter'),
      $fieldDefinition);
  }

}
