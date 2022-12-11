<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

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
    /** @var \Drupal\ock\DI\OckCallbackResolver $resolver */
    $resolver = $container->get(OckCallbackResolver::class);
    return $resolver->construct(static::class);
  }

}
