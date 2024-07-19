<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers some common compiler passes.
 */
class CommonServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    // Allow services to depend on the Psr service container.
    $container->setAlias(ContainerInterface::class, 'service_container')
      ->setPublic(true);
    ServiceProvider::fromCandidateObjects([
      new ServiceProvider_ParametricServices(),
      new ServiceProvider_ServiceModifierAttribute(),
    ])->register($container);
  }

}
