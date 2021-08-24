<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Cache;

interface Formula_Cache_FlatSelectInterface extends Formula_CacheBaseInterface {

  /**
   * @return string[]
   *   Format: $[$id] = $label
   */
  public function getOptions(): array;

}
