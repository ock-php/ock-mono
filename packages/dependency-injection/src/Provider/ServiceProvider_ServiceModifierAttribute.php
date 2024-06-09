<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\DependencyInjection\Compiler\ServiceModifierAttributePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceProvider_ServiceModifierAttribute implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    $container->addCompilerPass(new ServiceModifierAttributePass());
  }

}
