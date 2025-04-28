<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\Reflection\ClassReflection;
use Ock\Reflection\MethodReflection;

/**
 * @template TFactKey
 * @template TFact
 */
interface FactoryInspectorInterface {

  /**
   * Finds facts in a class or method.
   *
   * @param \Ock\Reflection\ClassReflection<object>|\Ock\Reflection\MethodReflection<object> $reflector
   *   Class or method to inspect.
   *
   * @return \Iterator<TFactKey, TFact>
   *   Facts found in the class or method.
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   *   Something went wrong, e.g. a bad declaration was found.
   */
  public function findInFactory(ClassReflection|MethodReflection $reflector): \Iterator;

}
