<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\OCUI\Util\UtilBase;

final class IdToFormula_Null extends UtilBase {

  /**
   * @return \Donquixote\OCUI\IdToFormula\IdToFormulaInterface
   */
  public static function create(): IdToFormulaInterface {
    return new IdToFormula_AlwaysTheSame(
      new Formula_ValueProvider_Null());
  }
}
