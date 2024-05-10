<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

/**
 * @template-implements \IteratorAggregate<mixed, \Donquixote\ClassDiscovery\Reflection\ClassReflection>
 */
interface ReflectionClassesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<int, \Donquixote\ClassDiscovery\Reflection\ClassReflection>
   */
  public function getIterator(): \Iterator;

}
