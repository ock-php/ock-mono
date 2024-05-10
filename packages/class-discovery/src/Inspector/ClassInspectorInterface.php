<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Inspector;

use Donquixote\ClassDiscovery\Reflection\ClassReflection;

/**
 * Inspector for a reflection class.
 *
 * @template TNeedle
 */
interface ClassInspectorInterface {

  /**
   * Finds results in a reflection class.
   *
   * @param ClassReflection $classReflection
   *
   * @return \Iterator<TNeedle>
   *
   * @throws \Donquixote\ClassDiscovery\Exception\DiscoveryException
   */
  public function findInClass(ClassReflection $classReflection): \Iterator;

}
