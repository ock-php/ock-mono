<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Cache\Prefix;

use Donquixote\ObCK\Cache\CacheInterface;
use Donquixote\ObCK\Cache\Offset\CacheOffset;
use Donquixote\ObCK\Cache\Offset\CacheOffsetInterface;

class CachePrefix_Root implements CachePrefixInterface {

  /**
   * @var \Donquixote\ObCK\Cache\CacheInterface
   */
  private $cache;

  /**
   * @param \Donquixote\ObCK\Cache\CacheInterface $cache
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
