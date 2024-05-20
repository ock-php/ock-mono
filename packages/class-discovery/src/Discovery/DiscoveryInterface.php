<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Discovery;

/**
 * @template TNeedle
 *
 * @template-extends \IteratorAggregate<TNeedle>
 */
interface DiscoveryInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<TNeedle>
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function getIterator(): \Iterator;

}
