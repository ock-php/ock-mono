<?php
declare(strict_types=1);

namespace Donquixote\Ock\Optionlessness;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\Ock\Util\UtilBase;

final class OptionlessnessSTAs extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return \Donquixote\Ock\Optionlessness\OptionlessnessInterface
   */
  public static function optionless(
    /** @noinspection PhpUnusedParameterInspection */ Formula_OptionlessInterface $formula
  ): OptionlessnessInterface {
    return new Optionlessness(TRUE);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Optionlessness\OptionlessnessInterface $formula
   *
   * @return \Donquixote\Ock\Optionlessness\Optionlessness
   */
  public static function optionlessness(OptionlessnessInterface $formula): Optionlessness {
    return new Optionlessness($formula->isOptionless());
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *
   * @return \Donquixote\Ock\Optionlessness\Optionlessness|null
   */
  public static function other(
    /** @noinspection PhpUnusedParameterInspection */ FormulaInterface $formula
  ): ?Optionlessness {
    return new Optionlessness(FALSE);
  }

}
