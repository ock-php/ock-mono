<?php

namespace Donquixote\ClassDiscovery\ClassFilesIA;

interface ClassFilesIAInterface extends \IteratorAggregate {

  /**
   * @return \Traversable|string[]
   *   Format: $[$file] = $class
   */
  public function getIterator();

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * @return self
   */
  public function withRealpathRoot();

}
