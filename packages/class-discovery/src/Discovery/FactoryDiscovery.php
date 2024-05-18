<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Discovery;

use Donquixote\ClassDiscovery\Inspector\FactoryInspector_Concat;
use Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Donquixote\ClassDiscovery\Reflection\ClassReflection;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_Concat;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Donquixote\Helpers\Util\MessageUtil;

/**
 * @template TNeedle
 *
 * @template-implements DiscoveryInterface<TNeedle>
 */
class FactoryDiscovery implements DiscoveryInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $classes
   * @param \Donquixote\ClassDiscovery\Inspector\FactoryInspectorInterface<TNeedle> $inspector
   */
  public function __construct(
    private readonly ReflectionClassesIAInterface $classes,
    private readonly FactoryInspectorInterface $inspector,
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
    $inspector = FactoryInspector_Concat::fromCandidateObjects($candidates);
    return new self($classes, $inspector);
  }

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->classes as $classReflection) {
      assert($classReflection instanceof ClassReflection, sprintf(
        'Expected only ClassReflection objects from %s, found %s.',
        MessageUtil::formatValue($this->classes),
        MessageUtil::formatValue($classReflection),
      ));
      yield from $this->inspector->findInFactory($classReflection);
      foreach ($classReflection->getMethods() as $methodReflection) {
        yield from $this->inspector->findInFactory($methodReflection);
      }
    }
  }

}
