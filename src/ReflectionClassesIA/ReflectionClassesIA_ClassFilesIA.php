<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class ReflectionClassesIA_ClassFilesIA implements ReflectionClassesIAInterface {

  /**
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
        $reflectionClass = new \ReflectionClass($class);
      }
      catch (\ReflectionException) {
        // Skip non-existing classes / interfaces / traits.
        continue;
      }
      catch (\Error) {
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
