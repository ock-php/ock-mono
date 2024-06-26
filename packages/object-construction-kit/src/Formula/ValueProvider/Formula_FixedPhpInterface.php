<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\ValueProvider;

use Ock\Ock\Formula\Optionless\Formula_OptionlessInterface;

interface Formula_FixedPhpInterface extends Formula_OptionlessInterface {

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(): string;

}
