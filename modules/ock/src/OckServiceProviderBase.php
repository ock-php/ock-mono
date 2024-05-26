<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Drupal\ock\DI\ResilientServiceAlias;
use Ock\ClassDiscovery\NamespaceDirectory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Base class for service providers that scan entire directories.
 */
abstract class OckServiceProviderBase implements ServiceProviderInterface {

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  public function register(ContainerBuilder $container): void {
    // The Drupal container builder makes all aliases public, thus prevengin
    // the removal of unused services.
    // Use a symfony ContainerBuilder instead.
    $new_builder = new ContainerBuilder();

    $this->doRegister($new_builder);

    $aliases = $new_builder->getAliases();
    foreach ($aliases as $id => $alias) {
      if ($alias->isPrivate()) {
        $aliases[$id] = ResilientServiceAlias::fromAlias($alias);
      }
    }
    $new_builder->setAliases($aliases);

    $container->merge($new_builder);
  }

  /**
   * Registers services discovered in the module's own namespace directory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   Container builder.
   *
   * @throws \Exception
   *   Malformed service declaration.
   */
  protected function doRegister(ContainerBuilder $container): void {
    $namespaceDir = NamespaceDirectory::fromKnownClass(static::class)->package();
    $instanceof = [];
    $anonymousCount = 0;
    $services = $this->createServicesConfigurator($container, $namespaceDir->getPackageDirectory(), $instanceof, $anonymousCount);
    // @todo Do something with $instanceof and $anonymousCount.
    $services->defaults()
      ->autowire()
      ->autoconfigure();
    $this->registerForCurrentModule($services, $namespaceDir);
  }

  /**
   * Registers services for the current module.
   *
   * @param \Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator $services
   *   Services configurator.
   * @param \Ock\ClassDiscovery\NamespaceDirectory $namespaceDir
   *   Module namespace directory.
   */
  protected function registerForCurrentModule(ServicesConfigurator $services, NamespaceDirectory $namespaceDir): void {
    $services->load($namespaceDir->getTerminatedNamespace(), $namespaceDir->getRelativeTerminatedPath());
  }

  /**
   * Creates a service configurator for a given container builder.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   Container builder.
   * @param string $dir
   *   Module or package directory.
   * @param array $instanceof
   *   This will be filled with instanceof definitions.
   * @param int $anonymousCount
   *   This will contain the anonymouse count.
   *
   * @return \Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator
   *   Services configurator with additional methods.
   */
  protected function createServicesConfigurator(ContainerBuilder $container, string $dir, array &$instanceof, int &$anonymousCount): ServicesConfigurator {
    $locator = new FileLocator($dir);
    $loader = new PhpFileLoader($container, $locator);
    return new ServicesConfigurator(
      $container,
      $loader,
      $instanceof,
      NULL,
      $anonymousCount,
    );
  }

}
