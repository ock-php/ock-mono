<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\MoreArgs;

use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;

interface Formula_MoreArgsInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return string
   */
  public function getSpecialKey(): string;

  /**
   * @return \Donquixote\Ock\Formula\Optionless\Formula_OptionlessInterface[]
   */
  public function getMoreArgs(): array;

}
