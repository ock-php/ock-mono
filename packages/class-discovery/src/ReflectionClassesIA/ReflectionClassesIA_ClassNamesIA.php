<?php

namespace Ock\ClassDiscovery\ReflectionClassesIA;

use Ock\ClassDiscovery\ClassNamesIA\ClassNamesIAInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;

class ReflectionClassesIA_ClassNamesIA implements ReflectionClassesIAInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\ClassNamesIA\ClassNamesIAInterface<mixed> $classNamesIA
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
