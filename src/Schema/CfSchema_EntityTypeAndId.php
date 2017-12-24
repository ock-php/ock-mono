<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown;
use Drupal\renderkit8\IdToSchema\IdToSchema_Et_EntityId;
use Drupal\renderkit8\Util\UtilBase;

final class CfSchema_EntityTypeAndId extends UtilBase {

  /**
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function create() {
    return CfSchema_Drilldown::create(
      CfSchema_EntityType::createOptionsSchema(),
      new IdToSchema_Et_EntityId())
      ->withKeys('entity_type', 'entity_id');
  }
}
