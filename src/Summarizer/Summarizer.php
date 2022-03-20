<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Exception\IncarnatorException;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Incarnator\Incarnator;
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
    try {
      $candidate = Incarnator::getObject(
        $formula,
        SummarizerInterface::class,
        $incarnator);
    }
    catch (IncarnatorException $e) {
      var_export($e);
      throw $e;
    }

    return $candidate;
  }

}
