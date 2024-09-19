<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * @template TFactKey
 * @template TFact
 *
 * @template-implements \Ock\ClassDiscovery\Inspector\PackageInspectorInterface<TFactKey, TFact>
 */
class PackageInspector_Concat implements PackageInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\PackageInspectorInterface<TFactKey, TFact>[] $inspectors
   *   Inspectors to dispatch the call to.
   */
  public function __construct(
    private readonly array $inspectors,
  ) {}

  /**
   * Checks if the inspector is empty.
   *
   * An empty inspector will not yield any facts.
   *
   * @return bool
   */
  public function isEmpty(): bool {
    return $this->inspectors === [];
  }

  /**
   * Creates a new instance from a list of inspector candidates.
   *
   * @param iterable<mixed> $candidates
   *   List of objects that may or may not be inspectors.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return static
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): static {
    $inspectors = [];
    foreach ($candidates as $candidate) {
      if ($candidate instanceof PackageInspectorInterface) {
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
  public function findInPackage(ReflectionClassesIAInterface $package): \Iterator {
    foreach ($this->inspectors as $inspector) {
      yield from $inspector->findInPackage($package);
    }
  }

}
