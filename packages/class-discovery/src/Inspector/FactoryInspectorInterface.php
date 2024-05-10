<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Inspector;

use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;

/**
 * @template TNeedle
 */
interface FactoryInspectorInterface {

  /**
   * Finds results in a class or method.
   *
   * @param FactoryReflectionInterface $reflector
   *   Class or method reflector.
   *
   * @return \Iterator<TNeedle>
   *   Declarations.
   *
   * @throws \Donquixote\DID\Exception\DiscoveryException
   *   Something went wrong, e.g. a bad declaration was found.
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator;

}
