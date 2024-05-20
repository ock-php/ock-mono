<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\Reflection\ClassReflection;

/**
 * Inspector that inspects the class and the methods.
 *
 * @template TNeedle
 *
 * @template-implements \Ock\ClassDiscovery\Inspector\ClassInspectorInterface<TNeedle>
 */
class ClassInspector_Factories implements ClassInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface $factoryInspector
   */
  public function __construct(
    private readonly FactoryInspectorInterface $factoryInspector,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    // Do not filter the class and methods.
    // This way, the factory inspector can warn about misplaced attributes,
    // instead of them being silently ignored.
    $this->factoryInspector->findInFactory($classReflection);
    foreach ($classReflection->getMethods() as $method) {
      yield from $this->factoryInspector->findInFactory($method);
    }
  }

}
