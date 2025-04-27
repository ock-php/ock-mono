<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

/**
 * @template-extends \IteratorAggregate<mixed, \Ock\Reflection\ClassReflection<object>>
 */
interface ReflectionClassesIAInterface extends \IteratorAggregate {

  /**
   * @return \Iterator<int, \Ock\Reflection\ClassReflection<object>>
   */
  public function getIterator(): \Iterator;

}
