<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_Composite;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\Proxy\Replacer\CfSchema_Proxy_ReplacerInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\renderkit8\Helper\FieldDefinitionLookup;

class CfSchema_FieldNameWithFormatter_SpecificEt implements CfSchema_Proxy_ReplacerInterface, IdToSchemaInterface {

  /**
   * @var \Drupal\renderkit8\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var \Drupal\renderkit8\Schema\CfSchema_FieldName
   */
  private $fieldNameSchemaProxy;

  /**
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function create($entityType, $bundleName = NULL) {

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $etm */
    # $etm = \Drupal::service('entity_type.manager');

    # $etm->

    return new self($entityType, $bundleName);
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   */
  public function __construct($entityType, $bundleName = NULL) {

    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();

    $this->entityType = $entityType;

    $this->fieldNameSchemaProxy = CfSchema_FieldName::create(
      $entityType,
      $bundleName);
  }

  /**
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function replacerGetSchema(SchemaReplacerInterface $replacer) {

    $fieldNameSchema = $replacer->schemaGetReplacement(
      $this->fieldNameSchemaProxy);

    if (!$fieldNameSchema instanceof CfSchema_OptionsInterface) {
      return NULL;
    }

    $schema = new CfSchema_Drilldown_Composite(
      $fieldNameSchema,
      $this);

    $schema = $schema->withKeys('field', 'display');

    $schema = CfSchema_DrilldownVal::createArrify($schema);

    return $schema;
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

    return CfSchema_FieldFormatterTypeAndSettings::create($fieldDefinition);
  }
}
