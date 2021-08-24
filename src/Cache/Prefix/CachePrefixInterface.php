<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Cache\Prefix;

use Donquixote\ObCK\Cache\Offset\CacheOffsetInterface;

interface CachePrefixInterface {

  /**
   * @param string $key
   *
   * @return \Donquixote\ObCK\Cache\Offset\CacheOffsetInterface
   */
  public function getOffset(string $key): CacheOffsetInterface;

  /**
   * @param string $prefix
   *
   * @return \Donquixote\ObCK\Cache\Prefix\CachePrefixInterface
   */
  public function withAppendedPrefix(string $prefix): self;

  /**
   * Clears this section of the cache.
   */
  public function clear(): void;

}
