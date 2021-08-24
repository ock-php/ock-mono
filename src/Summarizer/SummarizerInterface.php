<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Text\TextInterface;

interface SummarizerInterface {

  /**
   * @param mixed $conf
   *
   * @return null|\Donquixote\ObCK\Text\TextInterface
   */
  public function confGetSummary($conf): ?TextInterface;

}
