<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

/**
 * @template-extends \IteratorAggregate<mixed, \Ock\ClassDiscovery\Reflection\ClassReflection<object>>
 */
interface ReflectionClassesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<int, \Ock\ClassDiscovery\Reflection\ClassReflection<object>>
   */
  public function getIterator(): \Iterator;

}
