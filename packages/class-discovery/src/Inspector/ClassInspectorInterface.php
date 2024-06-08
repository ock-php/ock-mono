<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\Reflection\ClassReflection;

/**
 * Inspector for a reflection class.
 *
 * @template TFactKey
 * @template TFact
 */
interface ClassInspectorInterface {

  /**
   * Finds results in a reflection class.
   *
   * @param ClassReflection $classReflection
   *
   * @return \Iterator<TFactKey, TFact>
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function findInClass(ClassReflection $classReflection): \Iterator;

}
