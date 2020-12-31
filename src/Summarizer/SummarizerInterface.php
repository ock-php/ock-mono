<?php
declare(strict_types=1);

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Text\TextInterface;

interface SummarizerInterface {

  /**
   * @param mixed $conf
   *
   * @return null|\Donquixote\Cf\Text\TextInterface
   */
  public function confGetSummary($conf): ?TextInterface;

}
