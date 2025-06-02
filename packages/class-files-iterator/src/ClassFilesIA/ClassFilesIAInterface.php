<?php

namespace Ock\ClassFilesIterator\ClassFilesIA;

use Ock\ClassFilesIterator\ClassNamesIA\ClassNamesIAInterface;

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

}
