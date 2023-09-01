<?php

namespace Donquixote\ClassDiscovery\ReflectionClassesIA;

use Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;

class ReflectionClassesIA_ClassFilesIA implements ReflectionClassesIAInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface
   */
  private $classFilesIA;

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface $classFilesIA
   */
  public function __construct(ClassFilesIAInterface $classFilesIA) {
    $this->classFilesIA = $classFilesIA;
  }

  /**
   * @return \Traversable<int, \ReflectionClass<object>>
   *   Format: $[$file] = $class
   */
  public function getIterator(): \Traversable {

    foreach ($this->classFilesIA->withRealpathRoot() as $file => $class) {

      try {
        $reflectionClass = new \ReflectionClass($class);
      }
      catch (\ReflectionException $e) {
        // Skip non-existing classes / interfaces / traits.
        continue;
      }

      if ($file !== $reflectionClass->getFileName()) {
        continue;
      }

      yield $reflectionClass;
    }
  }
}
