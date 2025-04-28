<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\Reflection\FactoryReflectionInterface;

/**
 * @template TFactKey
 * @template TFact
 *
 * @template-implements \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface<TFactKey, TFact>
 */
class FactoryInspector_Concat implements FactoryInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface<TFactKey, TFact>[] $inspectors
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
      if ($candidate instanceof FactoryInspectorInterface) {
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
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    foreach ($this->inspectors as $inspector) {
      yield from $inspector->findInFactory($reflector);
    }
  }

}
