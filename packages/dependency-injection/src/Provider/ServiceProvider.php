<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\ClassDiscovery\FactsIA\FactsIA;
use Ock\DependencyInjection\Inspector\ClassInspector_ClassAsPrivateService;
use Ock\DependencyInjection\Inspector\ClassInspector_ConditionDecorator;
use Ock\DependencyInjection\Inspector\ClassInspector_SymfonyAsAliasAttributeDecorator;
use Ock\DependencyInjection\Inspector\FactoryInspector_ConditionDecorator;
use Ock\DependencyInjection\Inspector\FactoryInspector_ServiceAttribute;
use Ock\DependencyInjection\Inspector\PackageInspector_RegisterInterfacesReflection;
use Ock\DependencyInjection\Inspector\PackageInspector_SinglyImplementedInterfaceAliasDecorator;
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

  /**
   * @return list<object>
   *   Objects to send to ::fromCandidateObjects().
   *   This does not include namespaces.
   */
  public static function getDefaultInspectors(): array {
    return [
      ClassInspector_ClassAsPrivateService::create(),
      ClassInspector_SymfonyAsAliasAttributeDecorator::create(...),
      FactoryInspector_ServiceAttribute::create(),
      PackageInspector_SinglyImplementedInterfaceAliasDecorator::create(...),
      FactoryInspector_ConditionDecorator::create(...),
      ClassInspector_ConditionDecorator::create(...),
      PackageInspector_RegisterInterfacesReflection::decorateIfNeeded(...),
    ];
  }

}
