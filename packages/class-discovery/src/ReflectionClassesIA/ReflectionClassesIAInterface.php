<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

/**
 * @template-implements \IteratorAggregate<mixed, \Ock\ClassDiscovery\Reflection\ClassReflection>
 */
interface ReflectionClassesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<int, \Ock\ClassDiscovery\Reflection\ClassReflection>
   */
  public function getIterator(): \Iterator;

}
