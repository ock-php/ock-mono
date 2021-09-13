<?php
declare(strict_types=1);

namespace Donquixote\Ock\Cache\Prefix;

use Donquixote\Ock\Cache\Offset\CacheOffsetInterface;

interface CachePrefixInterface {

  /**
   * @param string $key
   *
   * @return \Donquixote\Ock\Cache\Offset\CacheOffsetInterface
   */
  public function getOffset(string $key): CacheOffsetInterface;

  /**
   * @param string $prefix
   *
   * @return \Donquixote\Ock\Cache\Prefix\CachePrefixInterface
   */
  public function withAppendedPrefix(string $prefix): self;

  /**
   * Clears this section of the cache.
   */
  public function clear(): void;

}
