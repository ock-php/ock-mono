<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Proxy\Replacer;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

interface Formula_Proxy_ReplacerInterface extends FormulaInterface {

  /**
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function replacerGetFormula(FormulaReplacerInterface $replacer): FormulaInterface;

}
