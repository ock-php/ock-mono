<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Cache;

interface CfSchema_Cache_SelectInterface extends CfSchema_CacheBaseInterface {

  /**
   * @return string[][]
   *   Format: $[$optgroupId][$id] = $label
   *   With $optgroupId = '' for toplevel options.
   */
  public function getGroupedOptions(): array;

  /**
   * @return string[]
   *   Format: $[$optgroupId] = $optgroupLabel
   */
  public function getOptgroupLabels(): array;

}
