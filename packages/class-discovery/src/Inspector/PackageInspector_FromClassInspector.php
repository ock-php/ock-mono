<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Package inspector checking classes and factories.
 *
 * @template TFactKey
 * @template TFact
 *
 * @template-implements \Ock\ClassDiscovery\Inspector\PackageInspectorInterface<TFactKey, TFact>
 */
class PackageInspector_FromClassInspector implements PackageInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface<TFactKey, TFact> $classInspector
   *   Class inspector.
   */
  public function __construct(
    private readonly ClassInspectorInterface $classInspector,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function findInPackage(ReflectionClassesIAInterface $package): \Iterator {
    foreach ($package as $classReflection) {
      yield from $this->classInspector->findInClass($classReflection);
    }
  }

}
