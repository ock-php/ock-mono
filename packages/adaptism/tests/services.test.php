<?php

/**
 * @file
 */

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
  $services = $container->services();

  $services->defaults()
    ->autowire()
    ->autoconfigure();

  $services->load('Ock\\Adaptism\\Tests\\Fixtures\\', 'src/Fixtures/');

  $services->set(\DateTimeZone::class)
    ->class(\DateTimeZone::class)
    ->public()
    ->arg(0, 'America/New_York');
};
