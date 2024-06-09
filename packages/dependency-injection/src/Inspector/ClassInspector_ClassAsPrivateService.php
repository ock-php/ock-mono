<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;

/**
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class ClassInspector_ClassAsPrivateService implements ClassInspectorInterface {

  use CreateDefinitionTrait;

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    if (!$classReflection->isInstantiable()) {
      // This could be an interface, a trait, or an abstract class, or a class
      // with private constructor.
      return;
    }
    $class = $classReflection->getName();
    // It must be a class.
    yield $class => $this->createDefinition($class);
  }

}
