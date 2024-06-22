<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceProvider_Concat implements ServiceProviderInterface {

  /**
   * Constructor.
   *
   * @param list<\Ock\DependencyInjection\Provider\ServiceProviderInterface>
   */
  public function __construct(
    private readonly array $providers,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    foreach ($this->providers as $provider) {
      $provider->register($container);
    }
  }

}
