<?php

namespace Donquixote\ClassDiscovery\ClassFilesIA;

class ClassFilesIA_Multiple implements ClassFilesIAInterface {

  /**
   * @var \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[]
   */
  private $classFilesIAs;

  /**
   * @param \Donquixote\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface[] $classFilesIAs
   */
  public function __construct(array $classFilesIAs) {
    $this->classFilesIAs = $classFilesIAs;
  }

  /**
   * @return \Traversable|string[]
   *   Format: $[$file] = $class
   */
  public function getIterator() {
    foreach ($this->classFilesIAs as $classFilesIA) {
      // @todo Use "yield from" in PHP 7!
      foreach ($classFilesIA as $file => $class) {
        yield $file => $class;
      }
    }
  }

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * @return self
   */
  public function withRealpathRoot() {
    $clone = clone $this;
    foreach ($clone->classFilesIAs as $i => $classFilesIA) {
      $clone->classFilesIAs[$i] = $classFilesIA->withRealpathRoot();
    }
    return $clone;
  }
}
