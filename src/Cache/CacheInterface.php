<?php
declare(strict_types=1);

namespace Donquixote\Cf\Cache;

interface CacheInterface {

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return bool
   */
  public function getInto($key, &$value): bool;

  /**
   * @param string $key
   * @param mixed $value
   */
  public function set($key, $value): void;

  /**
   * @param string $prefix
   */
  public function clear($prefix = ''): void;

}
