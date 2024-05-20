<?php

namespace Ock\ClassDiscovery\ClassFilesIA;

use Ock\ClassDiscovery\ClassNamesIA\ClassNamesIAInterface;

/**
 * @template-extends ClassNamesIAInterface<string>
 */
interface ClassFilesIAInterface extends ClassNamesIAInterface {

  /**
   * Iterates through class files.
   *
   * @return \Iterator<string, class-string>
   *   Format: $[$file] = $class
   */
  public function getIterator(): \Iterator;

  /**
   * Gets a version where all base paths are sent through ->realpath().
   *
   * This is useful when comparing the path to \ReflectionClass::getFileName().
   *
   * Implementations might use an optimization where they only send the base
   * path through realpath(), assuming that the subdirectories do not contain
   * symlinks.
   *
   * @return static
   */
  public function withRealpathRoot(): static;

}
