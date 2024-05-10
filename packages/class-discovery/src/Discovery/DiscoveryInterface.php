<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Discovery;

/**
 * @template TNeedle
 *
 * @template-extends \IteratorAggregate<TNeedle>
 */
interface DiscoveryInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<TNeedle>
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function getIterator(): \Iterator;

}
