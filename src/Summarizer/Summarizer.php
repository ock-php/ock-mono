<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param string $interface
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromIface(
    string $interface,
    NurseryInterface $formulaToAnything
  ): SummarizerInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Cannot build a generator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    NurseryInterface $formulaToAnything
  ): SummarizerInterface {

    /** @var \Donquixote\ObCK\Summarizer\SummarizerInterface $candidate */
    $candidate = $formulaToAnything->breed(
      $formula,
      SummarizerInterface::class);

    return $candidate;
  }

}
