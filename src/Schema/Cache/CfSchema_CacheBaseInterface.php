<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Cache;

interface CfSchema_CacheBaseInterface {

  /**
   * @return string
   */
  public function getCacheId(): string;

}
