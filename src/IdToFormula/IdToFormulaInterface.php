<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface IdToFormulaInterface {

  /**
   * @param string $id
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula(string $id): ?FormulaInterface;

}
