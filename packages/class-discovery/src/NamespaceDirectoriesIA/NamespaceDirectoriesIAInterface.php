<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\NamespaceDirectoriesIA;

/**
 * @template-extends \IteratorAggregate<string, \Ock\ClassDiscovery\NamespaceDirectory>
 */
interface NamespaceDirectoriesIAInterface extends \IteratorAggregate {

  /**
   * Iterates over namespace directories.
   *
   * @return \Iterator<string, \Ock\ClassDiscovery\NamespaceDirectory>
   *   Format: $[$name] = $nsdir.
   */
  public function getIterator(): \Iterator;

}
