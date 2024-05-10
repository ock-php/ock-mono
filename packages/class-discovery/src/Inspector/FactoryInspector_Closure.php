<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Inspector;

use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;

/**
 * Factory inspector based on a generator closure.
 *
 * This is useful in tests, to avoid mocking.
 *
 * This version expects a closure instead of an arbitrary callback, to keep it
 * more simple and predictable.
 *
 * @template TNeedle
 *
 * @template-implements \Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface<TNeedle>
 */
class FactoryInspector_Closure implements FactoryInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Closure(FactoryReflectionInterface): \Iterator<TNeedle> $closure
   *   Closure returning an iterator.
   */
  public function __construct(
    private readonly \Closure $closure,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    return $this->closure->__invoke($reflector);
  }

}
