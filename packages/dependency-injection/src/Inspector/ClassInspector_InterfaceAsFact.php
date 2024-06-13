<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;

/**
 * Reports interfaces as facts.
 *
 * Currently this is not really needed.
 *
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class ClassInspector_InterfaceAsFact implements ClassInspectorInterface {

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    if ($classReflection->isInterface()) {
      yield $classReflection->getName() => \T_INTERFACE;
    }
  }

}
