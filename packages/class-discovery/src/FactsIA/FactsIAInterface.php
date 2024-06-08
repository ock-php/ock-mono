<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\FactsIA;

/**
 * @template TFactKey
 * @template TFact
 *
 * @template-extends \IteratorAggregate<TFactKey, TFact>
 */
interface FactsIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<TFactKey, TFact>
   *   Facts iterator.
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function getIterator(): \Iterator;

}
