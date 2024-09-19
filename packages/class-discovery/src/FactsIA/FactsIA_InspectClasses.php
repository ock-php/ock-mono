<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\FactsIA;

use Ock\ClassDiscovery\Inspector\ClassInspector_Concat;
use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_Concat;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Finds results in reflection classes.
 *
 * This can be used instead of FactoryDiscovery, to allow to decorate the
 * inspectors on class level.
 *
 * @template TFactKey
 * @template TFact
 *
 * @template-implements FactsIAInterface<TFactKey, TFact>
 */
class FactsIA_InspectClasses implements FactsIAInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClasses
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface<TFactKey, TFact> $classInspector
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
   * @return self<mixed, mixed>
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): self {
    $classes = ReflectionClassesIA_Concat::fromCandidateObjects($candidates);
    $inspector = ClassInspector_Concat::fromCandidateObjects($candidates, true);
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
