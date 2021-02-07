<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Proxy\Replacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

interface Formula_Proxy_ReplacerInterface extends FormulaInterface {

  /**
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function replacerGetFormula(FormulaReplacerInterface $replacer): FormulaInterface;

}
