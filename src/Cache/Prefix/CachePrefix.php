<?php
declare(strict_types=1);

namespace Donquixote\Cf\Cache\Prefix;

use Donquixote\Cf\Cache\CacheInterface;
use Donquixote\Cf\Cache\Offset\CacheOffset;
use Donquixote\Cf\Cache\Offset\CacheOffsetInterface;

class CachePrefix implements CachePrefixInterface {

  /**
   * @var \Donquixote\Cf\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $prefix;

  /**
   * @param \Donquixote\Cf\Cache\CacheInterface $cache
   * @param string $prefix
   */
  public function __construct(CacheInterface $cache, $prefix) {
    $this->cache = $cache;
    $this->prefix = $prefix;
  }

  /**
   * {@inheritdoc}
   */
  public function getOffset(string $key): CacheOffsetInterface {
    return new CacheOffset($this->cache, $this->prefix . $key);
  }

  /**
   * {@inheritdoc}
   */
  public function withAppendedPrefix(string $prefix): CachePrefixInterface {
    return new self($this->cache, $this->prefix . $prefix);
  }

  /**
   * {@inheritdoc}
   */
  public function clear(): void {
    $this->cache->clear($this->prefix);
  }
}
