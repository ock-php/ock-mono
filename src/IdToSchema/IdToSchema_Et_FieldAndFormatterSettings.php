<?php

namespace Drupal\renderkit8\IdToSchema;

use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Drupal\renderkit8\Schema\CfSchema_EntityType_WithFields;
use Drupal\renderkit8\Schema\CfSchema_FieldNameWithFormatter_SpecificEt;

class IdToSchema_Et_FieldAndFormatterSettings implements IdToSchemaInterface {

  /**
   * @return \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface
   */
  public static function createDrilldownValSchema() {

    return CfSchema_DrilldownVal::createArrify(
      self::createDrilldownSchema());
  }

  /**
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function createDrilldownSchema() {

    $etSelector = new CfSchema_EntityType_WithFields(
      \Drupal::service('entity_field.manager'),
      \Drupal::service('entity_type.repository'));

    return CfSchema_Drilldown::create(
      $etSelector,
      new self())
      ->withKeys('entity_type', 'field_and_formatter');
  }

  /**
   * @param string $entityType
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityType) {

    $schema = CfSchema_FieldNameWithFormatter_SpecificEt::create($entityType);

    return new CfSchema_Label($schema, t('Field'));
  }
}
