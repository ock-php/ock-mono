<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

interface SummarizerInterface {

  /**
   * @param mixed $conf
   *
   * @return null|string
   */
  public function confGetSummary($conf);

}
