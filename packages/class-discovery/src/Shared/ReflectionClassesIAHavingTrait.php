<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\Shared;

use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Trait for all kinds of discovery classes.
 */
trait ReflectionClassesIAHavingTrait {

  /**
   * @var \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface|null
   */
  private ?ReflectionClassesIAInterface $reflectionClassesIA = NULL;

  /**
   * Immutable setter. Sets the classes iterator.
   *
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClassesIA
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
   * @param \Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
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
