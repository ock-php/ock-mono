<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;
use Drupal\renderkit8\IdToSchema\IdToSchema_Et_FieldName;
use Drupal\renderkit8\Util\UtilBase;

/**
 * Schema where the value is like ['entity_type' => 'node', 'field_name' => 'body'].
 */
final class CfSchema_EtAndFieldName extends UtilBase {

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function create(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundleName = NULL
  ) {

    // @todo Real dependency injection.

    if (NULL !== $entityType) {
      return new CfSchema_Group(
        [
          // @todo This should be _FixedConf instead.
          'entity_type' => new CfSchema_ValueProvider_FixedValue($entityType),
          'field_name' => new CfSchema_FieldName_AllowedTypes(
            $entityType,
            $bundleName,
            $allowedFieldTypes),
        ],
        [
          'entity_type' => t('Entity type'),
          'field_name' => t('Field name'),
        ]);
    }

    return CfSchema_DrilldownVal::createArrify(
      CfSchema_Drilldown::create(
        CfSchema_EntityType::createOptionsSchema(),
        new IdToSchema_Et_FieldName(
          $allowedFieldTypes,
          $bundleName))
        ->withKeys('entity_type', 'field_name'));

  }
}
