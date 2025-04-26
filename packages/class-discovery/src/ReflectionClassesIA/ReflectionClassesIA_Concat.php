<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface;

/**
 * Concatenation of multiple reflection classes iterator aggregates.
 */
class ReflectionClassesIA_Concat implements ReflectionClassesIAInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface[] $parts
   *   List of reflection classes iterator aggregates.
   */
  public function __construct(
    private readonly array $parts = [],
  ) {
    // Validate the input array, if assertions are enabled.
    assert([] === array_filter(
      $parts,
      fn ($value) => !$value instanceof ReflectionClassesIAInterface,
    ));
  }

  /**
   * Creates a new instance from a list of candidate objects.
   *
   * @param object[] $candidates
   *   List of objects that may or may not be class iterators.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return self
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): self {
    $classesIAs = [];
    foreach ($candidates as $candidate) {
      if ($candidate instanceof ReflectionClassesIAInterface) {
        $classesIAs[] = $candidate;
      }
      elseif ($candidate instanceof ClassFilesIAInterface) {
        $classesIAs[] = new ReflectionClassesIA_ClassFilesIA($candidate);
      }
    }
    return new self($classesIAs);
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->parts as $part) {
      yield from $part;
    }
  }

}
