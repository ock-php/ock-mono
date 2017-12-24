<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\Proxy\Replacer\CfSchema_Proxy_ReplacerInterface;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Drupal\renderkit8\IdToSchema\IdToSchema_FieldName_FormatterTypeAndSettings;

class CfSchema_FieldNameWithFormatter_SpecificEt implements CfSchema_Proxy_ReplacerInterface {

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
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function create($entityType, $bundleName = NULL) {

    return new self($entityType, $bundleName);
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   */
  public function __construct($entityType, $bundleName = NULL) {

    $this->entityType = $entityType;

    $this->fieldNameSchemaProxy = CfSchema_FieldName::create(
      $entityType,
      $bundleName);
  }

  /**
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function replacerGetSchema(SchemaReplacerInterface $replacer): CfSchemaInterface {

    $fieldNameSchema = $replacer->schemaGetReplacement(
      $this->fieldNameSchemaProxy);

    if (!$fieldNameSchema instanceof CfSchema_SelectInterface) {
      return NULL;
    }

    $idToSchema = new IdToSchema_FieldName_FormatterTypeAndSettings($this->entityType);

    $drilldownSchema = CfSchema_Drilldown::create($fieldNameSchema, $idToSchema)
      ->withKeys('field', 'display');

    $drilldownValSchema = CfSchema_DrilldownVal::createArrify($drilldownSchema);

    return $drilldownValSchema;
  }
}
