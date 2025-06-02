<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\ClassFilesIterator\ClassFilesIA\ClassFilesIAInterface;
use Ock\Reflection\ClassReflection;

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
    foreach ($this->classFilesIA as $file => $class) {
      try {
        $reflectionClass = new ClassReflection($class);
      }
      catch (\ReflectionException|\Error) {
        // Skip non-existing classes / interfaces / traits.
        // Skip if a base class or interface is missing.
        // Unfortunately, missing traits still cause fatal error.
        continue;
      }
      // Skip a class that is defined elsewhere.
      // Optimize for the case where both paths are already the same, without
      // calling realpath().
      if ($file !== $reflectionClass->getFileName()) {
        if ($reflectionClass->getFileName() === false) {
          // This is a built-in class.
          continue;
        }
        if (realpath($file) !== $reflectionClass->getFileName()
          && realpath($file) !== realpath($reflectionClass->getFileName())
        ) {
          continue;
        }
      }
      yield $reflectionClass;
    }
  }

}
