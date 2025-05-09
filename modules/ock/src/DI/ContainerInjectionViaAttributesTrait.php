<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

use Drupal\service_discovery\CallbackResolverInterface;
use Psr\Container\ContainerInterface;

trait ContainerInjectionViaAttributesTrait {

  /**
   * Static factory.
   *
   * @param \Psr\Container\ContainerInterface $container
   *
   * @return static
   *
   * @throws \Exception
   *   Some arguments are left unresolved.
   */
  public static function create(ContainerInterface $container): static {
    $resolver = $container->get(CallbackResolverInterface::class);
    \assert($resolver instanceof CallbackResolverInterface);
    return $resolver->construct(static::class);
  }

}
