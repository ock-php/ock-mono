<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

abstract class InlineDrilldown_DecoKey implements InlineDrilldownInterface {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function fromFormula(Formula_DecoKeyInterface $formula, NurseryInterface $formulaToAnything) {
    return InlineDrilldown::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything);
  }

}
