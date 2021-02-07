<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\IdToFormula\IdToFormula_Null;
use Donquixote\OCUI\Formula\Select\Formula_SelectInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Formula_Drilldown_SelectFormulaNull extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Formula\Select\Formula_SelectInterface $optionsFormula
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(Formula_SelectInterface $optionsFormula): Formula_DrilldownInterface {
    return new Formula_Drilldown(
      $optionsFormula,
      IdToFormula_Null::create());
  }
}
