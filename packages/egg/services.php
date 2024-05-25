<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
  #$classes = NamespaceDirectory::fromKnownClass(EggNamespace::class);
  #SymfonyServiceDiscovery::create()->discover($container, $classes);
  #return;

  $services = $container->services();

  $services->defaults()
    ->autowire()
    ->autoconfigure();

  // The path is relative to the package root where 'services.php' is located.
  $services->load('Ock\\Egg\\', 'src/');
};

