<?php
declare(strict_types=1);

namespace Donquixote\Cf\Cache;

interface CacheInterface {

  /**
   * Reads a cache value into a variable.
   *
   * @param string $key
   *   Cache key.
   * @param mixed $value
   *   (by reference) Variable that will contain the value.
   *
   * @return bool
   *   TRUE if found, FALSE if not found.
   */
  public function getInto(string $key, &$value): bool;

  /**
   * Writes a cache value.
   *
   * @param string $key
   *   Cache key.
   * @param mixed $value
   *   Value to write.
   */
  public function set(string $key, $value): void;

  /**
   * Clears cache entries.
   *
   * @param string $prefix
   *   (optional) Prefix for cache keys to be cleared.
   */
  public function clear($prefix = ''): void;

}
