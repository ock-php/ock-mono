<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\NamespaceDirectoriesIA;

interface NamespaceDirectoriesIAInterface extends \IteratorAggregate {

  /**
   * Iterates over namespace directories.
   *
   * @return \Iterator<string, \Ock\ClassDiscovery\NamespaceDirectory>
   *   Format: $[$name] = $nsdir.
   */
  public function getIterator(): \Iterator;

}
