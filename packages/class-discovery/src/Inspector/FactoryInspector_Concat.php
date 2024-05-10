<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Inspector;

use Donquixote\ClassDiscovery\Reflection\FactoryReflectionInterface;

/**
 * @template TNeedle
 *
 * @template-implements \Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface<TNeedle>
 */
class FactoryInspector_Concat implements FactoryInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface[] $inspectors
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
