<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

abstract class IncarnatorBase implements IncarnatorInterface {

  private string $cacheId;

  /**
   * Constructor.
   *
   * @param string $cache_id
   */
  public function __construct(string $cache_id) {
    $this->cacheId = $cache_id;
  }

  public function getCacheId(): string {
    return $this->cacheId;
  }

}
