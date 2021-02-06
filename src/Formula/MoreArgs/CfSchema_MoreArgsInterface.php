<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\MoreArgs;

use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_MoreArgsInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return string
   */
  public function getSpecialKey(): string;

  /**
   * @return \Donquixote\OCUI\Formula\Optionless\CfSchema_OptionlessInterface[]
   */
  public function getMoreArgs(): array;

}
