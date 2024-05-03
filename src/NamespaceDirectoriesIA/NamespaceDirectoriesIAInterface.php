<?php

declare(strict_types=1);

namespace Donquixote\ClassDiscovery\NamespaceDirectoriesIA;

interface NamespaceDirectoriesIAInterface extends \IteratorAggregate {

  /**
   * Iterates over namespace directories.
   *
   * @return \Iterator<string, \Donquixote\ClassDiscovery\NamespaceDirectory>
   *   Format: $[$name] = $nsdir.
   */
  public function getIterator(): \Iterator;

}
