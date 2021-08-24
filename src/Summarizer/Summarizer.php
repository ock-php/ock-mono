<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Util\MessageUtil;
use Donquixote\ObCK\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
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
