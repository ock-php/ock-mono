<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\NamespaceDirectoriesIA;

/**
 * @template-extends \IteratorAggregate<string, \Ock\ClassFilesIterator\NamespaceDirectory>
 */
interface NamespaceDirectoriesIAInterface extends \IteratorAggregate {

  /**
   * Iterates over namespace directories.
   *
   * @return \Iterator<string, \Ock\ClassFilesIterator\NamespaceDirectory>
   *   Format: $[$name] = $nsdir.
   */
  public function getIterator(): \Iterator;

}
