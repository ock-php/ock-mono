<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Para;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;

interface Formula_ParaInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getParaFormula(): FormulaInterface;

}
