<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_SelectSchemaBase;

class CfSchema_EntityTypeAndId extends CfSchema_Drilldown_SelectSchemaBase {

  public function __construct() {
    parent::__construct(
      CfSchema_EntityType::createOptionsSchema());
  }

  /**
   * @param string|int $entityTypeId
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($entityTypeId) {
    return new CfSchema_EntityId($entityTypeId);
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
    return 'entity_id';
  }
}
