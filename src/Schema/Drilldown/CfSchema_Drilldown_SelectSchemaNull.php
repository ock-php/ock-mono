<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchema_Null;
use Donquixote\OCUI\Schema\Select\CfSchema_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class CfSchema_Drilldown_SelectSchemaNull extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Schema\Select\CfSchema_SelectInterface $optionsSchema
   *
   * @return \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function create(CfSchema_SelectInterface $optionsSchema): CfSchema_DrilldownInterface {
    return new CfSchema_Drilldown(
      $optionsSchema,
      IdToSchema_Null::create());
  }
}
