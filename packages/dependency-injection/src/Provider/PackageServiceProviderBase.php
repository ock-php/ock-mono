<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Provider;

use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class PackageServiceProviderBase implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function register(ContainerBuilder $container): void {
    $provider = ServiceProvider::fromCandidateObjects([
      ...$this->getPackages(),
      ...$this->getInspectors(),
    ]);
    $provider->register($container);
  }

  /**
   * @return list<\Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface>
   */
  protected function getPackages(): array {
    return [
      ReflectionClassesIA::psr4FromKnownClass(static::class),
    ];
  }

  /**
   * @return list<object>
   */
  protected function getInspectors(): array {
    return ServiceProvider::getDefaultInspectors();
  }

}
