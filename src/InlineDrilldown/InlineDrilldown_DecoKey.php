<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;

abstract class InlineDrilldown_DecoKey implements InlineDrilldownInterface {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromFormula(Formula_DecoKeyInterface $formula, NurseryInterface $formulaToAnything) {
    return InlineDrilldown::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything);
  }

}
