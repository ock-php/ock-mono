<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Text\TextInterface;

interface SummarizerInterface {

  /**
   * @param mixed $conf
   *
   * @return null|\Donquixote\OCUI\Text\TextInterface
   */
  public function confGetSummary($conf): ?TextInterface;

}
