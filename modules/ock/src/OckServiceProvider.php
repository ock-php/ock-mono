<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\service_discovery\ModuleServiceProviderBase;
use Ock\Adaptism\AdaptismPackage;
use Ock\Egg\EggPackage;
use Ock\Ock\OckPackage;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Service provider for the Ock module.
 */
class OckServiceProvider extends ModuleServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function doRegister(ContainerBuilder $container): void {
    // Register services in the composer packages.
    (new EggPackage())->register($container);
    (new AdaptismPackage())->register($container);
    (new OckPackage())->register($container);

    // Register packages in the module itself.
    parent::doRegister($container);
  }

}
