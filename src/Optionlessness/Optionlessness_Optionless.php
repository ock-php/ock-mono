<?php

declare(strict_types=1);

namespace Donquixote\Ock\Optionlessness;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface;
use Donquixote\Ock\Util\UtilBase;

/**
 * Incarnator from Formula_Optionless* to Optionlessness*.
 */
final class Optionlessness_Optionless extends UtilBase {

  /**
   * @param \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface $formula
   *
   * @return \Donquixote\Ock\Optionlessness\OptionlessnessInterface
   */
  #[OckIncarnator]
  public static function fromFormula(Formula_OptionlessInterface $formula): OptionlessnessInterface {
    return new Optionlessness(TRUE);
  }

}
