<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

/**
 * @template TKey
 * @template-covariant TValue
 * @template-implements \IteratorAggregate<TKey, TValue>
 */
interface ReflectionClassesIAInterface extends \IteratorAggregate {

  /**
   * @return \Traversable<int, \ReflectionClass<object>>
   *   Format: $[$file] = $class
   */
  public function getIterator(): \Traversable;

}
