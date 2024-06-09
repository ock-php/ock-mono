<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Compiler\RegisterAutoconfigureAttributesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @see \Symfony\Component\DependencyInjection\Compiler\RegisterAutoconfigureAttributesPass
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::findClasses()
 */
class ClassInspector_SymfonyAutoconfigureAttribute implements ClassInspectorInterface {

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    if ($classReflection->isInstantiable()) {
      // The autoconfigure attributes will be handled when the compiler pass is
      // invoked in the regular way, when the container is compiled.
      return;
    }
    if (!$classReflection->hasAttributes(Autoconfigure::class)) {
      // The expensive operation can be skipped.
      return;
    }
    // The autoconfigure needs to happen here.
    // This replicates the mechanism from symfony's FileLoader::findClasses().
    yield static function (ContainerBuilder $container) use ($classReflection): void {
      $pass = new RegisterAutoconfigureAttributesPass();
      $pass->processClass($container, $classReflection);
    };
  }

}
