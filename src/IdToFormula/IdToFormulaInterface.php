<?php

declare(strict_types=1);

namespace Donquixote\Ock\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface IdToFormulaInterface {

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula(string|int $id): ?FormulaInterface;

}
