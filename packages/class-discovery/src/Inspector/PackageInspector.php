<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Inspector;

/**
 * Static factories for package inspectors.
 *
 * @see \Ock\ClassDiscovery\Inspector\PackageInspectorInterface
 */
class PackageInspector {

  /**
   * Creates a new instance from a list of inspector candidates.
   *
   * @param iterable<mixed> $candidates
   *   List of objects that may or may not be class or factory inspectors.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return \Ock\ClassDiscovery\Inspector\PackageInspectorInterface
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): PackageInspectorInterface {
    $inspectors = [];
    $classInspector = ClassInspector::fromCandidateObjects($candidates, true);
    if (!$classInspector instanceof ClassInspector_Concat
      || !$classInspector->isEmpty()
    ) {
      $inspectors[] = new PackageInspector_FromClassInspector($classInspector);
    }
    foreach ($candidates as $candidate) {
      if ($candidate instanceof PackageInspectorInterface) {
        $inspectors[] = $candidate;
      }
    }
    if (count($inspectors) !== 1) {
      $packageInspector = new PackageInspector_Concat($inspectors);
    }
    else {
      $packageInspector = $inspectors[0];
    }
    return static::applyDecorators($packageInspector, $candidates);
  }

  /**
   * @param \Ock\ClassDiscovery\Inspector\PackageInspectorInterface $decorated
   * @param iterable<mixed> $candidates
   *   Objects which may or may not contain decorator closures.
   *
   * @return \Ock\ClassDiscovery\Inspector\PackageInspectorInterface
   */
  public static function applyDecorators(PackageInspectorInterface $decorated, iterable $candidates): PackageInspectorInterface {
    foreach ($candidates as $candidate) {
      if (!$candidate instanceof \Closure) {
        continue;
      }
      $rf = new \ReflectionFunction($candidate);
      $params = $rf->getParameters();
      if (count($params) !== 1) {
        continue;
      }
      $type = $params[0]->getType();
      if (!$type) {
        continue;
      }
      if ($type->__toString() !== PackageInspectorInterface::class) {
        continue;
      }
      $decorator = $candidate($decorated);
      if (!$decorator instanceof PackageInspectorInterface) {
        continue;
      }
      $decorated = $decorator;
    }
    return $decorated;
  }

}
