<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\PackagesIA;

/**
 * @template-extends \IteratorAggregate<string, \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface>
 */
interface PackagesIAInterface extends \IteratorAggregate {

  /**
   * Iterates over searchable packages.
   *
   * @return \Iterator<string, \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface>
   *   Iterator keys are package directory paths.
   *   Iterator values are reflection class iterator aggregates.
   *
   * @todo The signature may need to change if we need to support exclude
   *   patterns per package.
   */
  public function getIterator(): \Iterator;

}
