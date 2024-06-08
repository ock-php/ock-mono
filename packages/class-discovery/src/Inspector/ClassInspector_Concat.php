<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\Reflection\ClassReflection;

/**
 * @template TFactKey
 * @template TFact
 *
 * @template-implements \Ock\ClassDiscovery\Inspector\ClassInspectorInterface<TFactKey, TFact>
 */
class ClassInspector_Concat implements ClassInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface<TFactKey, TFact>[] $inspectors
   *   Inspectors to dispatch the call to.
   */
  public function __construct(
    private readonly array $inspectors,
  ) {}

  /**
   * Creates a new instance from a list of inspector candidates.
   *
   * @param array $candidates
   *   List of objects that may or may not be inspectors.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return static
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): static {
    $inspectors = [];
    foreach ($candidates as $candidate) {
      if ($candidate instanceof ClassInspectorInterface) {
        $inspectors[] = $candidate;
      }
    }
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($inspectors);
  }

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    foreach ($this->inspectors as $inspector) {
      yield from $inspector->findInClass($classReflection);
    }
  }

}
