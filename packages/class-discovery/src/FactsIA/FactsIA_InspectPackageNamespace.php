<?php

declare(strict_types = 1);

namespace Ock\ClassDiscovery\FactsIA;

use Ock\ClassDiscovery\Inspector\PackageInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;

/**
 * Finds facts in a single package namespace.
 *
 * @template TFactKey
 * @template TFact
 *
 * @template-implements FactsIAInterface<TFactKey, TFact>
 */
class FactsIA_InspectPackageNamespace implements FactsIAInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $reflectionClasses
   * @param \Ock\ClassDiscovery\Inspector\PackageInspectorInterface<TFactKey, TFact> $packageInspector
   */
  public function __construct(
    private readonly ReflectionClassesIAInterface $reflectionClasses,
    private readonly PackageInspectorInterface $packageInspector,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getIterator(): \Iterator {
    return $this->packageInspector->findInPackage($this->reflectionClasses);
  }

}
