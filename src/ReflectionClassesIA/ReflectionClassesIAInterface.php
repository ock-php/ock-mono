<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

/**
 * @template-implements \IteratorAggregate<mixed, \ReflectionClass>
 */
interface ReflectionClassesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<int, \ReflectionClass>
   */
  public function getIterator(): \Iterator;

}
