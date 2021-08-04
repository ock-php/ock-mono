<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\OCUI\Util\MessageUtil;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   *   Cannot build a generator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    FormulaToAnythingInterface $formulaToAnything
  ): SummarizerInterface {

    $candidate = $formulaToAnything->formula(
      $formula,
      SummarizerInterface::class);

    if ($candidate instanceof SummarizerInterface) {
      return $candidate;
    }

    throw new \RuntimeException(strtr(
      'Misbehaving FTA for formula of class @formula_class: Expected @interface object, found @found.',
      [
        '@formula_class' => get_class($formula),
        '@interface' => SummarizerInterface::class,
        '@found' => MessageUtil::formatValue($candidate),
      ]));
  }

}
