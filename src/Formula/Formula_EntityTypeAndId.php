<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Drupal\renderkit\IdToFormula\IdToFormula_Et_EntityId;
use Drupal\renderkit\Util\UtilBase;

final class Formula_EntityTypeAndId extends UtilBase {

  /**
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(): Formula_DrilldownInterface {
    return Formula_Drilldown::create(
      Formula_EntityType::create(),
      new IdToFormula_Et_EntityId())
      ->withKeys('entity_type', 'entity_id');
  }
}
