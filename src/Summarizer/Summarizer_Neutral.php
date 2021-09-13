<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {


  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Neutral\Formula_NeutralInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function create(Formula_NeutralInterface $formula, NurseryInterface $formulaToAnything): SummarizerInterface {
    return Summarizer::fromFormula($formula->getDecorated(), $formulaToAnything);
  }
}
