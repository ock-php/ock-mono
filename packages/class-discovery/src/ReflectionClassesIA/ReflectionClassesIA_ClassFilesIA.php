<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Donquixote\ClassDiscovery\Reflection\ClassReflection;

class ReflectionClassesIA_ClassFilesIA implements ReflectionClassesIAInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   */
  public function __construct(
    private readonly ClassFilesIAInterface $classFilesIA,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->classFilesIA->withRealpathRoot() as $file => $class) {
      try {
        $reflectionClass = new ClassReflection($class);
      }
      catch (\ReflectionException|\Error) {
        // Skip non-existing classes / interfaces / traits.
        // Skip if a base class or interface is missing.
        // Unfortunately, missing traits still cause fatal error.
        return null;
      }
      if ($file !== $reflectionClass->getFileName()) {
        continue;
      }
      yield $reflectionClass;
    }
  }

}
