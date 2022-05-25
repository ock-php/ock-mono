<?php

declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Ock\Formula\Boolean\Formula_BooleanInterface;
use Donquixote\Ock\Text\TextInterface;

#[Adapter]
class Summarizer_Boolean implements SummarizerInterface {

  /**
   * @param \Donquixote\Ock\Formula\Boolean\Formula_BooleanInterface $formula
   */
  public function __construct(
    private readonly Formula_BooleanInterface $formula,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {

    $boolean = !empty($conf);

    return $boolean
      ? $this->formula->getTrueSummary()
      : $this->formula->getFalseSummary();
  }

}
