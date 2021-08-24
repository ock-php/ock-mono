<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_Null;
use Donquixote\ObCK\Util\UtilBase;

final class IdToFormula_Null extends UtilBase {

  /**
   * @return \Donquixote\ObCK\IdToFormula\IdToFormulaInterface
   */
  public static function create(): IdToFormulaInterface {
    return new IdToFormula_AlwaysTheSame(
      new Formula_ValueProvider_Null());
  }
}
