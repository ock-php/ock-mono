<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Para;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;

interface Formula_ParaInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getParaFormula(): FormulaInterface;

}
