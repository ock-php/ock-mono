<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Summarizer;

use Donquixote\OCUI\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\OCUI\Util\UtilBase;

final class Summarizer_Optionless extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optionless\Formula_OptionlessInterface $schema
   *
   * @return \Donquixote\OCUI\Summarizer\SummarizerInterface
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ Formula_OptionlessInterface $schema
  ): SummarizerInterface {
    return new Summarizer_Null();
  }
}
