<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Discovery;

use Donquixote\ClassDiscovery\Inspector\ClassInspector_Concat;
use Donquixote\ClassDiscovery\Inspector\ClassInspectorInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_Concat;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Finds results in reflection classes.
 *
 * This can be used instead of FactoryDiscovery, to allow to decorate the
 * inspectors on class level.
 *
 * @template TNeedle
 *
 * @template-implements DiscoveryInterface<TNeedle>
 */
class ClassDiscovery implements DiscoveryInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClasses
   * @param \Donquixote\ClassDiscovery\Inspector\ClassInspectorInterface<TNeedle> $classInspector
   */
  public function __construct(
    private readonly ReflectionClassesIAInterface $reflectionClasses,
    private readonly ClassInspectorInterface $classInspector,
  ) {}

  /**
   * Creates a new instance from a list of candidate objects.
   *
   * @param iterable<object> $candidates
   *   Sequence of objects which may contain inspectors and class lists.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return self
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): self {
    $classes = ReflectionClassesIA_Concat::fromCandidateObjects($candidates);
    $inspector = ClassInspector_Concat::fromCandidateObjects($candidates);
    return new self($classes, $inspector);
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->reflectionClasses as $reflectionClass) {
      yield from $this->classInspector->findInClass($reflectionClass);
    }
  }

}
