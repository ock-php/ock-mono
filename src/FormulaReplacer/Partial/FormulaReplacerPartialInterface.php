<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

interface FormulaReplacerPartialInterface {

  /**
   * @return string
   */
  public function getSourceFormulaClass(): string;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function formulaGetReplacement(FormulaInterface $formula, FormulaReplacerInterface $replacer): ?FormulaInterface;

}
