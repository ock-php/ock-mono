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
   * Finds facts in a reflection class.
   *
   * @param ClassReflection $classReflection
   *   Class to inspect.
   *
   * @return \Iterator<TFactKey, TFact>
   *   Facts found when inspecting the class.
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function findInClass(ClassReflection $classReflection): \Iterator;

}
