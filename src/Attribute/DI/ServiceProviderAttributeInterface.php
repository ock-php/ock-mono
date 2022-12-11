<?php

/**
 * @file
 */

declare(strict_types = 1);

namespace Drupal\ock\Attribute\DI;

use Drupal\Core\DependencyInjection\ContainerBuilder;

interface ServiceProviderAttributeInterface {

  /**
   * @param \Drupal\Core\DependencyInjection\ContainerBuilder $container
   * @param \ReflectionClass|\ReflectionMethod $reflector
   *
   * @throws \Exception
   *   Bad declaration.
   */
  public function register(ContainerBuilder $container, \ReflectionClass|\ReflectionMethod $reflector): void;

}
