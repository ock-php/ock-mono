<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers some common compiler passes.
 */
class CommonServiceProvider implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    ServiceProvider::fromCandidateObjects([
      new ServiceProvider_ParametricServices(),
      new ServiceProvider_ServiceModifierAttribute(),
    ])->register($container);
  }

}
