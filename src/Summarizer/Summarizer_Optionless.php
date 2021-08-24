<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Summarizer;

use Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\ObCK\Util\UtilBase;

final class Summarizer_Optionless extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return \Donquixote\ObCK\Summarizer\SummarizerInterface
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ Formula_OptionlessInterface $formula
  ): SummarizerInterface {
    return new Summarizer_Null();
  }
}
