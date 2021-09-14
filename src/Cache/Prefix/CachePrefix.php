<?php

declare(strict_types=1);

namespace Donquixote\Ock\Cache\Prefix;

use Donquixote\Ock\Cache\CacheInterface;
use Donquixote\Ock\Cache\Offset\CacheOffset;
use Donquixote\Ock\Cache\Offset\CacheOffsetInterface;

class CachePrefix implements CachePrefixInterface {

  /**
   * @var \Donquixote\Ock\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $prefix;

  /**
   * @param \Donquixote\Ock\Cache\CacheInterface $cache
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
