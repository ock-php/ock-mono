<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\MoreArgs;

use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;

interface Formula_MoreArgsInterface extends Formula_ValueToValueBaseInterface {

  /**
   * @return string
   */
  public function getSpecialKey(): string;

  /**
   * @return \Donquixote\OCUI\Formula\Optionless\Formula_OptionlessInterface[]
   */
  public function getMoreArgs(): array;

}
