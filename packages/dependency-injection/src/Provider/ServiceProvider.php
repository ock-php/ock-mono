<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\ClassDiscovery\FactsIA\FactsIA;
use function Ock\Helpers\array_filter_instanceof;

class ServiceProvider {

  /**
   * @param iterable $candidates
   *
   * @return \Ock\DependencyInjection\Provider\ServiceProviderInterface
   */
  public static function fromCandidateObjects(iterable $candidates): ServiceProviderInterface {
    $candidates = \iterator_to_array($candidates, false);
    $factsIA = FactsIA::fromCandidateObjects($candidates);
    $providers = [
      ...array_filter_instanceof($candidates, ServiceProviderInterface::class),
      new ServiceProvider_FactsIA($factsIA),
    ];
    return new ServiceProvider_Concat($providers);
  }

}
