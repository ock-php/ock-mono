<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface FormulaReplacerInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
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
