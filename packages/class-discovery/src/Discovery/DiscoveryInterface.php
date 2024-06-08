<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Discovery;

/**
 * @template TFactKey
 * @template TFact
 *
 * @template-extends \IteratorAggregate<TFactKey, TFact>
 */
interface DiscoveryInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<TFactKey, TFact>
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function getIterator(): \Iterator;

}
