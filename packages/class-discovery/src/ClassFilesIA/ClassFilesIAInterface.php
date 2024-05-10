<?php

namespace Donquixote\ClassDiscovery\ClassFilesIA;

/**
 * @template-extends \IteratorAggregate<string, class-string>
 */
interface ClassFilesIAInterface extends \IteratorAggregate {

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
