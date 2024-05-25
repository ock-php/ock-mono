<?php

/**
 * @file
 * Service definitions.
 *
 * @see \Drupal\ock\OckServiceProvider
 */

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return function (ContainerConfigurator $container): void {
  $services = $container->services();

  $services->set('logger.channel.ock')
    ->parent('logger.channel_base')
    ->arg(0, 'ock');

  $services->defaults()
    ->autowire()
    ->autoconfigure()
    ->bind(LoggerInterface::class, new Reference('logger.channel.ock'))
  ;

  $services->alias(ContainerInterface::class, 'service_container');

  $services->load('Drupal\\ock\\', 'src/');
};
