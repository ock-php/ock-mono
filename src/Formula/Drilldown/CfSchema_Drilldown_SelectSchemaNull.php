<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchema_Null;
use Donquixote\OCUI\Formula\Select\CfSchema_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class CfSchema_Drilldown_SelectSchemaNull extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Formula\Select\CfSchema_SelectInterface $optionsSchema
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(CfSchema_SelectInterface $optionsSchema): Formula_DrilldownInterface {
    return new CfSchema_Drilldown(
      $optionsSchema,
      IdToSchema_Null::create());
  }
}
