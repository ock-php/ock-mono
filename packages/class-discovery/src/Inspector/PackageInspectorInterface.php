<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Inspector;

use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Inspector for a package.
 *
 * @template TFactKey
 * @template TFact
 */
interface PackageInspectorInterface {

  /**
   * Finds facts in a package namespace directory.
   *
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $package
   *   Package namespace directory.
   *
   * @return \Iterator<TFactKey, TFact>
   *
   * @throws \Ock\ClassDiscovery\Exception\DiscoveryException
   */
  public function findInPackage(ReflectionClassesIAInterface $package): \Iterator;

}
