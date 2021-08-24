<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Text\TextInterface;

class Summarizer_Null implements SummarizerInterface {

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {
    return NULL;
  }
}
