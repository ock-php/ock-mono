<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Cache\Prefix;

use Donquixote\OCUI\Cache\CacheInterface;
use Donquixote\OCUI\Cache\Offset\CacheOffset;
use Donquixote\OCUI\Cache\Offset\CacheOffsetInterface;

class CachePrefix_Root implements CachePrefixInterface {

  /**
   * @var \Donquixote\OCUI\Cache\CacheInterface
   */
  private $cache;

  /**
   * @param \Donquixote\OCUI\Cache\CacheInterface $cache
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
