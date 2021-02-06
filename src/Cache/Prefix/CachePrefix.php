<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Cache\Prefix;

use Donquixote\OCUI\Cache\CacheInterface;
use Donquixote\OCUI\Cache\Offset\CacheOffset;
use Donquixote\OCUI\Cache\Offset\CacheOffsetInterface;

class CachePrefix implements CachePrefixInterface {

  /**
   * @var \Donquixote\OCUI\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $prefix;

  /**
   * @param \Donquixote\OCUI\Cache\CacheInterface $cache
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
