<?php

/**
 * @file
 */

declare(strict_types=1);
namespace Ock\DependencyInjection\Provider;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers services from a facts iterator into a container builder.
 */
interface ServiceProviderInterface {

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   */
  public function register(ContainerBuilder $container): void;

}
