<?php
declare(strict_types=1);

namespace Donquixote\Ock\Cache\Prefix;

use Donquixote\Ock\Cache\CacheInterface;
use Donquixote\Ock\Cache\Offset\CacheOffset;
use Donquixote\Ock\Cache\Offset\CacheOffsetInterface;

class CachePrefix_Root implements CachePrefixInterface {

  /**
   * @var \Donquixote\Ock\Cache\CacheInterface
   */
  private $cache;

  /**
   * @param \Donquixote\Ock\Cache\CacheInterface $cache
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
