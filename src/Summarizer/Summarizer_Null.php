<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Text\TextInterface;

class Summarizer_Null implements SummarizerInterface {

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf): ?TextInterface {
    return NULL;
  }
}
