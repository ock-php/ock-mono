<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\DependencyInjection\Compiler\InsertParametricArgumentsPlaceholdersPass;
use Ock\DependencyInjection\Compiler\ResolveParametricArgumentsPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceProvider_ParametricServices implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    $container->addCompilerPass(
      new InsertParametricArgumentsPlaceholdersPass(),
      PassConfig::TYPE_OPTIMIZE,
      10,
    );
    $container->addCompilerPass(
      new ResolveParametricArgumentsPass(),
      PassConfig::TYPE_OPTIMIZE,
      -10,
    );
  }

}
