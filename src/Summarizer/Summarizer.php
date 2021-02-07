<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface|null
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): ?SummarizerInterface {

    $candidate = $formulaToAnything->formula(
      $formula,
      SummarizerInterface::class);

    if (!$candidate instanceof SummarizerInterface) {
      return NULL;
    }

    return $candidate;
  }

}
