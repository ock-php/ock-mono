<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Drilldown;

use Donquixote\ObCK\IdToFormula\IdToFormula_Null;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Formula_Drilldown_SelectFormulaNull extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Formula\Select\Formula_SelectInterface $optionsFormula
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(Formula_SelectInterface $optionsFormula): Formula_DrilldownInterface {
    return new Formula_Drilldown(
      $optionsFormula,
      IdToFormula_Null::create());
  }
}
