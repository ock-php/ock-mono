<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface IdToFormulaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula(string $id): ?FormulaInterface;

}
