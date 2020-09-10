<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

class Summarizer_Null implements SummarizerInterface {

  /**
   * {@inheritdoc}
   */
  public function confGetSummary($conf) {
    return NULL;
  }
}
