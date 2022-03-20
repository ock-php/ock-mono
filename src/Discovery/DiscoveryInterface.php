<?php

declare(strict_types=1);

namespace Donquixote\Ock\Discovery;

/**
 * @template T
 */
interface DiscoveryInterface {

  /**
   * @param \Exception[][]|NULL $exceptions
   *   Before: Array or NULL.
   *   During, after: List of exceptions.
   *
   * @return \Iterator<T>
   */
  public function discover(?array &$exceptions): \Iterator;

}
