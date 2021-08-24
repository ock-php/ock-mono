<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\MoreArgs;

use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;

interface Formula_MoreArgsInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return string
   */
  public function getSpecialKey(): string;

  /**
   * @return \Donquixote\ObCK\Formula\Optionless\Formula_OptionlessInterface[]
   */
  public function getMoreArgs(): array;

}
