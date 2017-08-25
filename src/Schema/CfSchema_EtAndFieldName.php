<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_SelectSchemaBase;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;

/**
 * Schema where the value is like ['entity_type' => 'node', 'field_name' => 'body'].
 */
class CfSchema_EtAndFieldName extends CfSchema_Drilldown_SelectSchemaBase {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function create(array $allowedFieldTypes = NULL, $entityType = NULL, $bundleName = NULL) {

    // @todo Real dependency injection.

    if (NULL !== $entityType) {
      return new CfSchema_Group(
        [
          // @todo This should be _FixedConf instead.
          'entity_type' => new CfSchema_ValueProvider_FixedValue($entityType),
          'field_name' => CfSchema_FieldName_AllowedTypesX::create(
            $allowedFieldTypes,
            $entityType,
            $bundleName),
        ],
        [
          'entity_type' => t('Entity type'),
          'field_name' => t('Field name'),
        ]);
    }

    return CfSchema_DrilldownVal::createArrify(
      new self(
        $allowedFieldTypes,
        $bundleName));

  }

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string|null $bundleName
   */
  public function __construct(
    array $allowedFieldTypes = NULL,
    $bundleName = NULL
  ) {
    $this->allowedFieldTypes = $allowedFieldTypes;
    $this->bundleName = $bundleName;

    parent::__construct(CfSchema_EntityType::createOptionsSchema());
  }

  /**
   * @param string|int $entityTypeId
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityTypeId) {

    return CfSchema_FieldName_AllowedTypesX::create(
      $this->allowedFieldTypes,
      $entityTypeId,
      $this->bundleName);
  }

  /**
   * @return string
   */
  public function getIdKey() {
    return 'entity_type';
  }

  /**
   * @return string
   */
  public function getOptionsKey() {
    return 'field_name';
  }
}
