<?php

declare(strict_types = 1);

namespace Donquixote\ClassDiscovery\Shared;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Trait for all kinds of discovery classes.
 */
trait ReflectionClassesIAHavingTrait {

  /**
   * @var \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface|null
   */
  private ?ReflectionClassesIAInterface $reflectionClassesIA = NULL;

  /**
   * Immutable setter. Sets the classes iterator.
   *
   * @param \Donquixote\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
   *
   * @return static
   */
  public function withReflectionClassesIA(ReflectionClassesIAInterface $reflectionClassesIA): static {
    $clone = clone $this;
    $clone->reflectionClassesIA = $reflectionClassesIA;
    return $clone;
  }

  /**
   * Immutable setter. Sets the classes iterator based on class files iterator.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   *
   * @return static
   */
  public function withClassFilesIA(ClassFilesIAInterface $classFilesIA): static {
    $clone = clone $this;
    $clone->reflectionClassesIA = new ReflectionClassesIA_ClassFilesIA($classFilesIA);
    return $clone;
  }

  /**
   * Iterates over the reflection classes.
   *
   * The NULL case is handled as empty iterator.
   *
   * @return \Iterator<int, \ReflectionClass<object>>
   */
  protected function itReflectionClasses(): \Iterator {
    if ($this->reflectionClassesIA !== NULL) {
      yield from $this->reflectionClassesIA;
    }
  }

}
