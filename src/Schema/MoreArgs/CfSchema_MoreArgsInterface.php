<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\MoreArgs;

use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_MoreArgsInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return string
   */
  public function getSpecialKey(): string;

  /**
   * @return \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface[]
   */
  public function getMoreArgs(): array;

}
