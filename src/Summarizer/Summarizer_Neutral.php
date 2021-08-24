<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Neutral\Formula_NeutralInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_NeutralInterface $formula, FormulaToAnythingInterface $formulaToAnything): SummarizerInterface {
    return Summarizer::fromFormula($formula->getDecorated(), $formulaToAnything);
  }
}
