<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassNamesIA\ClassNamesIAInterface;
use Donquixote\ClassDiscovery\Reflection\ClassReflection;

class ReflectionClassesIA_ClassNamesIA implements ReflectionClassesIAInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\ClassDiscovery\ClassNamesIA\ClassNamesIAInterface $classNamesIA
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
        return null;
      }
      yield $reflectionClass;
    }
  }

}
