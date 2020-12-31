<?php
declare(strict_types=1);

namespace Donquixote\Cf\Cache\Prefix;

use Donquixote\Cf\Cache\Offset\CacheOffsetInterface;

interface CachePrefixInterface {

  /**
   * @param string $key
   *
   * @return \Donquixote\Cf\Cache\Offset\CacheOffsetInterface
   */
  public function getOffset(string $key): CacheOffsetInterface;

  /**
   * @param string $prefix
   *
   * @return \Donquixote\Cf\Cache\Prefix\CachePrefixInterface
   */
  public function withAppendedPrefix(string $prefix): self;

  /**
   * Clears this section of the cache.
   */
  public function clear(): void;

}
