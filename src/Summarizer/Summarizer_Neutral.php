<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Neutral\Formula_NeutralInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function create(Formula_NeutralInterface $formula, FormulaToAnythingInterface $formulaToAnything): SummarizerInterface {
    return Summarizer::fromFormula($formula->getDecorated(), $formulaToAnything);
  }
}
