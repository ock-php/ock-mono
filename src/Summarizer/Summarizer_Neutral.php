<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {

  /**
   * @param \Donquixote\Ock\Formula\Neutral\Formula_NeutralInterface $formula
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  #[OckIncarnator]
  public static function create(Formula_NeutralInterface $formula, IncarnatorInterface $incarnator): SummarizerInterface {
    return Summarizer::fromFormula($formula->getDecorated(), $incarnator);
  }

}
