<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaBase;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal;
use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Schema\ValueProvider\CfSchema_ValueProvider_FixedValue;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;

class CfSchema_EtAndFieldNameAndFormatterSettings extends CfSchema_Drilldown_OptionsSchemaBase {

  /**
   * @param null $entityType
   * @param null $bundleName
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function create($entityType = NULL, $bundleName = NULL) {

    if (NULL === $entityType) {
      return CfSchema_DrilldownVal::createArrify(
        new self(
          \Drupal::service('entity_field.manager'),
          \Drupal::service('entity_type.repository')));
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

  /**
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   * @param \Drupal\Core\Entity\EntityTypeRepositoryInterface $entityTypeRepository
   */
  public function __construct(
    EntityFieldManagerInterface $entityFieldManager,
    EntityTypeRepositoryInterface $entityTypeRepository
  ) {
    parent::__construct(
      new CfSchema_EntityType_WithFields(
        $entityFieldManager,
        $entityTypeRepository));
  }

  /**
   * @param string $entityType
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityType) {

    return CfSchema_FieldNameWithFormatter_SpecificEt::create($entityType);
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
    return 'field_and_formatter';
  }

}
