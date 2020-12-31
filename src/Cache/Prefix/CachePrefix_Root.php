<?php
declare(strict_types=1);

namespace Donquixote\Cf\Cache\Prefix;

use Donquixote\Cf\Cache\CacheInterface;
use Donquixote\Cf\Cache\Offset\CacheOffset;
use Donquixote\Cf\Cache\Offset\CacheOffsetInterface;

class CachePrefix_Root implements CachePrefixInterface {

  /**
   * @var \Donquixote\Cf\Cache\CacheInterface
   */
  private $cache;

  /**
   * @param \Donquixote\Cf\Cache\CacheInterface $cache
   */
  public function __construct(CacheInterface $cache) {
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public function getOffset(string $key): CacheOffsetInterface {
    return new CacheOffset($this->cache, $key);
  }

  /**
   * {@inheritdoc}
   */
  public function withAppendedPrefix(string $prefix): CachePrefixInterface {
    return new CachePrefix($this->cache, $prefix);
  }

  /**
   * {@inheritdoc}
   */
  public function clear(): void {
    $this->cache->clear();
  }
}
