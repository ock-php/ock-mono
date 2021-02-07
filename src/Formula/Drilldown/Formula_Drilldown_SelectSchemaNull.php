<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToSchema\IdToSchema_Null;
use Donquixote\OCUI\Formula\Select\Formula_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Formula_Drilldown_SelectSchemaNull extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Formula\Select\Formula_SelectInterface $optionsSchema
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(Formula_SelectInterface $optionsSchema): Formula_DrilldownInterface {
    return new Formula_Drilldown(
      $optionsSchema,
      IdToSchema_Null::create());
  }
}
