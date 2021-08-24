<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer;

use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface FormulaReplacerInterface {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   *   A transformed formula, or NULL if no replacement can be found.
   */
  public function formulaGetReplacement(FormulaInterface $formula): ?FormulaInterface;

  /**
   * @param string $formulaClass
   *
   * @return bool
   */
  public function acceptsFormulaClass(string $formulaClass): bool;

}
