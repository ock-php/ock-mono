<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
