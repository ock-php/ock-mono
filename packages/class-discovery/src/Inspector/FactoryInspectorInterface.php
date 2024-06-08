<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;

/**
 * @template TFactKey
 * @template TFact
 */
interface FactoryInspectorInterface {

  /**
   * Finds results in a class or method.
   *
   * @param FactoryReflectionInterface $reflector
   *   Class or method reflector.
   *
   * @return \Iterator<TFactKey, TFact>
   *   Declarations.
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   *   Something went wrong, e.g. a bad declaration was found.
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator;

}
