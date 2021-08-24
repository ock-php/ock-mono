<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Cache\Offset;

interface CacheOffsetInterface {

  /**
   * @param mixed $value
   *
   * @return bool
   */
  public function getInto(&$value): bool;

  /**
   * @param mixed $value
   */
  public function set($value): void;

}
