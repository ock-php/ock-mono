<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\IdToSchema\IdToSchema_Null;
use Donquixote\Cf\Schema\Select\CfSchema_SelectInterface;
use Donquixote\Cf\Util\UtilBase;

final class CfSchema_Drilldown_SelectSchemaNull extends UtilBase {

  /**
   * @param \Donquixote\Cf\Schema\Select\CfSchema_SelectInterface $optionsSchema
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function create(CfSchema_SelectInterface $optionsSchema): CfSchema_DrilldownInterface {
    return new CfSchema_Drilldown(
      $optionsSchema,
      IdToSchema_Null::create());
  }
}
