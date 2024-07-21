<?php

declare(strict_types = 1);

namespace Drupal\service_discovery;

use Ock\DependencyInjection\Provider\CommonServiceProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Service provider for this module.
 */
class ServiceDiscoveryServiceProvider extends ModuleServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function doRegister(ContainerBuilder $container): void {
    // Register services and/or compiler passes from the package.
    (new CommonServiceProvider())->register($container);

  }

}
