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
   * @param array $candidates
   *   List of objects that may or may not be class or factory inspectors.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return \Ock\ClassDiscovery\Inspector\PackageInspectorInterface
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): PackageInspectorInterface {
    $inspectors = [];
    $classInspector = ClassInspector_Concat::fromCandidateObjects($candidates, true);
    if (!$classInspector->isEmpty()) {
      $inspectors[] = new PackageInspector_FromClassInspector($classInspector);
    }
    foreach ($candidates as $candidate) {
      if ($candidate instanceof PackageInspectorInterface) {
        $inspectors[] = $candidate;
      }
    }
    if (count($inspectors) === 1) {
      return $inspectors[0];
    }
    return new PackageInspector_Concat($inspectors);
  }

}
