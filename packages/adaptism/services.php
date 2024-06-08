<?php

declare(strict_types = 1);

use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\FactsIA\FactsIAInterface;
use Ock\ClassDiscovery\FactsIA\FactsIA_InspectFactories;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $container): void {
  $services = $container->services();

  $services->defaults()
    ->autowire()
    ->autoconfigure();

  // The path is relative to the package root where 'services.php' is located.
  $services->load('Ock\\Adaptism\\', 'src/');

  $services->set(FactsIAInterface::class . ' $' . AdaptismPackage::DISCOVERY_TARGET)
    ->public()
    ->class(FactsIA_InspectFactories::class)
    ->factory([FactsIA_InspectFactories::class, 'fromCandidateObjects'])
    ->arg(0, tagged_iterator(AdaptismPackage::DISCOVERY_TAG_NAME));
};
