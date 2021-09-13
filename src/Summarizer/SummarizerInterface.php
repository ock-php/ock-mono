<?php
declare(strict_types=1);

namespace Donquixote\Ock\Summarizer;

use Donquixote\Ock\Text\TextInterface;

interface SummarizerInterface {

  /**
   * @param mixed $conf
   *
   * @return null|\Donquixote\Ock\Text\TextInterface
   */
  public function confGetSummary($conf): ?TextInterface;

}
