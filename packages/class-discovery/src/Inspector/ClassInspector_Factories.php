<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Inspector;

use Donquixote\ClassDiscovery\Reflection\ClassReflection;

/**
 * Inspector that inspects the class and the methods.
 *
 * @template TNeedle
 *
 * @template-implements \Donquixote\ClassDiscovery\Inspector\ClassInspectorInterface<TNeedle>
 */
class ClassInspector_Factories implements ClassInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface $factoryInspector
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
