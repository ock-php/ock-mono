<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Summarizer_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(
    Formula_ValueToValueBaseInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?SummarizerInterface {
    return Summarizer::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything
    );
  }

}
