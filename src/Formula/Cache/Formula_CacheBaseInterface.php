<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Cache;

interface Formula_CacheBaseInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

}
