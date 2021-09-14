<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class Summarizer extends UtilBase {

  /**
   * @param string $interface
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromIface(
    string $interface,
    IncarnatorInterface $incarnator
  ): SummarizerInterface {
    return self::fromFormula(
      Formula::iface($interface),
      $incarnator);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Cannot build a generator for the given formula.
   */
  public static function fromFormula(
    FormulaInterface $formula,
    IncarnatorInterface $incarnator
  ): SummarizerInterface {

    /** @var \Donquixote\Ock\Summarizer\SummarizerInterface $candidate */
    $candidate = $incarnator->incarnate(
      $formula,
      SummarizerInterface::class);

    return $candidate;
  }

}
