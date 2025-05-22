<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIAInterface;
use Ock\Reflection\ClassReflection;

class ReflectionClassesIA_ClassNamesIA implements ReflectionClassesIAInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIAInterface<mixed> $classNamesIA
   */
  public function __construct(
    private readonly ClassNamesIAInterface $classNamesIA,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    foreach ($this->classNamesIA as $class) {
      try {
        $reflectionClass = new ClassReflection($class);
      }
      catch (\ReflectionException|\Error) {
        // Skip non-existing classes / interfaces / traits.
        // Skip if a base class or interface is missing.
        // Unfortunately, missing traits still cause fatal error.
        continue;
      }
      yield $reflectionClass;
    }
  }

}
