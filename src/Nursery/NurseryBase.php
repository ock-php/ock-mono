<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery;

abstract class NurseryBase implements NurseryInterface {

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
