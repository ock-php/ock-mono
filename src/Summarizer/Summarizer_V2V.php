<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class Summarizer_V2V extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function create(
    Formula_ValueToValueBaseInterface $formula,
    IncarnatorInterface $incarnator
  ): ?SummarizerInterface {
    return Summarizer::fromFormula(
      $formula->getDecorated(),
      $incarnator
    );
  }

}
