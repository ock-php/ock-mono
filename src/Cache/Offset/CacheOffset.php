<?php

declare(strict_types=1);

namespace Donquixote\Ock\Cache\Offset;

use Donquixote\Ock\Cache\CacheInterface;

class CacheOffset implements CacheOffsetInterface {

  /**
   * @var \Donquixote\Ock\Cache\CacheInterface
   */
  private $cache;

  /**
   * @var string
   */
  private $key;

  /**
   * @param \Donquixote\Ock\Cache\CacheInterface $cache
   * @param string $key
   */
  public function __construct(CacheInterface $cache, string $key) {
    $this->cache = $cache;
    $this->key = $key;
  }

  /**
   * {@inheritdoc}
   */
  public function getInto(&$value): bool {
    return $this->cache->getInto($this->key, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function set($value): void {
    $this->cache->set($this->key, $value);
  }

}
