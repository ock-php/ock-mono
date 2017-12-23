<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;
use Drupal\renderkit8\IdToSchema\IdToSchema_Et_FieldAndFormatterSettings;
use Drupal\renderkit8\Util\UtilBase;

final class CfSchema_EtAndFieldNameAndFormatterSettings extends UtilBase {

  /**
   * @param null $entityType
   * @param null $bundleName
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function create($entityType = NULL, $bundleName = NULL) {

    if (NULL === $entityType) {
      $schema = IdToSchema_Et_FieldAndFormatterSettings::createDrilldownValSchema();

      return new CfSchema_Label($schema, t('Entity type'));
    }

    return new CfSchema_Group(
      [
        'entity_type' => new CfSchema_ValueProvider_FixedValue($entityType),
        'field_and_formatter' => CfSchema_FieldNameWithFormatter_SpecificEt::create(
          $entityType,
          $bundleName),
      ],
      [
        t('Entity type'),
        t('Field and formatter'),
      ]);
  }

}
