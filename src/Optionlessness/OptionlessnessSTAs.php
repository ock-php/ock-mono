<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Optionlessness;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\ObCK\Util\UtilBase;

final class OptionlessnessSTAs extends UtilBase {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return \Donquixote\ObCK\Optionlessness\OptionlessnessInterface
   */
  public static function optionless(
    /** @noinspection PhpUnusedParameterInspection */ Formula_OptionlessInterface $formula
  ): OptionlessnessInterface {
    return new Optionlessness(TRUE);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Optionlessness\OptionlessnessInterface $formula
   *
   * @return \Donquixote\ObCK\Optionlessness\Optionlessness
   */
  public static function optionlessness(OptionlessnessInterface $formula): Optionlessness {
    return new Optionlessness($formula->isOptionless());
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *
   * @return \Donquixote\ObCK\Optionlessness\Optionlessness|null
   */
  public static function other(
    /** @noinspection PhpUnusedParameterInspection */ FormulaInterface $formula
  ): ?Optionlessness {
    return new Optionlessness(FALSE);
  }

}
