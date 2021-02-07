<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Optionlessness;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\OCUI\Util\UtilBase;

final class OptionlessnessSTAs extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return \Donquixote\OCUI\Optionlessness\OptionlessnessInterface
   */
  public static function optionless(
    /** @noinspection PhpUnusedParameterInspection */ Formula_OptionlessInterface $formula
  ): OptionlessnessInterface {
    return new Optionlessness(TRUE);
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Optionlessness\OptionlessnessInterface $formula
   *
   * @return \Donquixote\OCUI\Optionlessness\Optionlessness
   */
  public static function optionlessness(OptionlessnessInterface $formula): Optionlessness {
    return new Optionlessness($formula->isOptionless());
  }

  /**
   * @STA
   *
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   *
   * @return \Donquixote\OCUI\Optionlessness\Optionlessness|null
   */
  public static function other(
    /** @noinspection PhpUnusedParameterInspection */ FormulaInterface $formula
  ): ?Optionlessness {
    return new Optionlessness(FALSE);
  }

}
