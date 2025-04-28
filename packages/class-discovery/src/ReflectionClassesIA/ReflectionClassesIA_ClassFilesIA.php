<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\Reflection\ClassReflection;
use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface;

class ReflectionClassesIA_ClassFilesIA implements ReflectionClassesIAInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface $classFilesIA
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
        continue;
      }
      if ($file !== $reflectionClass->getFileName()) {
        continue;
      }
      yield $reflectionClass;
    }
  }

}
