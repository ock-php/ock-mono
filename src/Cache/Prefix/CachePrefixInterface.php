<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Cache\Prefix;

use Donquixote\OCUI\Cache\Offset\CacheOffsetInterface;

interface CachePrefixInterface {

  /**
   * @param string $key
   *
   * @return \Donquixote\OCUI\Cache\Offset\CacheOffsetInterface
   */
  public function getOffset(string $key): CacheOffsetInterface;

  /**
   * @param string $prefix
   *
   * @return \Donquixote\OCUI\Cache\Prefix\CachePrefixInterface
   */
  public function withAppendedPrefix(string $prefix): self;

  /**
   * Clears this section of the cache.
   */
  public function clear(): void;

}
