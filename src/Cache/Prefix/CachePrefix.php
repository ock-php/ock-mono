<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Cache\Prefix;

use Donquixote\ObCK\Cache\CacheInterface;
use Donquixote\ObCK\Cache\Offset\CacheOffset;
use Donquixote\ObCK\Cache\Offset\CacheOffsetInterface;

class CachePrefix implements CachePrefixInterface {

  /**
   * @var \Donquixote\ObCK\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $prefix;

  /**
   * @param \Donquixote\ObCK\Cache\CacheInterface $cache
   * @param string $prefix
   */
  public function __construct(CacheInterface $cache, string $prefix) {
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
