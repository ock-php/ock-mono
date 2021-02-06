<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Cache;

interface CfSchema_Cache_FlatSelectInterface extends CfSchema_CacheBaseInterface {

  /**
   * @return string[]
   *   Format: $[$id] = $label
   */
  public function getOptions(): array;

}
