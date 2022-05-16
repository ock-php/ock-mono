<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Formula\Neutral\Formula_NeutralInterface;
use Donquixote\Ock\Util\UtilBase;

final class Summarizer_Neutral extends UtilBase {

  /**
   * @param \Donquixote\Ock\Formula\Neutral\Formula_NeutralInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\Summarizer\SummarizerInterface
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function create(Formula_NeutralInterface $formula, UniversalAdapterInterface $universalAdapter): SummarizerInterface {
    return Summarizer::fromFormula($formula->getDecorated(), $universalAdapter);
  }

}
