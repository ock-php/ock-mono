<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\FactsIA;

use Ock\ClassDiscovery\Inspector\PackageInspector;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use function Ock\Helpers\array_filter_instanceof;

/**
 * Class with static factories.
 */
class FactsIA {

  /**
   * @param iterable<mixed> $candidates
   *
   * @return \Ock\ClassDiscovery\FactsIA\FactsIAInterface
   */
  public static function fromCandidateObjects(iterable $candidates): FactsIAInterface {
    $candidates = \iterator_to_array($candidates, false);
    $packageInspector = PackageInspector::fromCandidateObjects($candidates);
    $packages = array_filter_instanceof($candidates, ReflectionClassesIAInterface::class);
    $factsIAs = [
      ...array_filter_instanceof($candidates, FactsIAInterface::class),
      ...array_map(
        fn (ReflectionClassesIAInterface $package) => new FactsIA_InspectPackageNamespace(
          $package,
          $packageInspector,
        ),
        $packages,
      ),
    ];
    return new FactsIA_Concat($factsIAs);
  }

}
