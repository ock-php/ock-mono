<?php

declare(strict_types = 1);

use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\Discovery\DiscoveryInterface;
use Ock\ClassDiscovery\Discovery\FactoryDiscovery;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $container): void {
  $services = $container->services();

  $services->defaults()
    ->autowire()
    ->autoconfigure();

  // The path is relative to the package root where 'services.php' is located.
  $services->load('Ock\\Adaptism\\', 'src/');

  $services->set(DiscoveryInterface::class . ' $' . AdaptismPackage::DISCOVERY_TARGET)
    ->public()
    ->class(FactoryDiscovery::class)
    ->factory([FactoryDiscovery::class, 'fromCandidateObjects'])
    ->arg(0, tagged_iterator(AdaptismPackage::DISCOVERY_TAG_NAME));
};
