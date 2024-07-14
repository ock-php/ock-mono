<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\Ock\Attribute\DI\PublicService;
use Ock\Adaptism\AdaptismPackage;
use Ock\ClassDiscovery\NamespaceDirectory;
use Ock\DID\DidNamespace;
use Ock\Egg\EggNamespace;
use Ock\Ock\OckPackage;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Service provider for the Ock module.
 */
class OckServiceProvider extends OckServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function doRegister(ContainerBuilder $container): void {
    // Register services in the composer packages.
    static::loadPackageServicesPhp($container, dirname(DidNamespace::DIR));
    static::loadPackageServicesPhp($container, dirname(EggNamespace::DIR));
    (new AdaptismPackage())->register($container);
    (new OckPackage())->register($container);

    // Make services public that have the
    $container->registerAttributeForAutoconfiguration(
      PublicService::class,
      static function (ChildDefinition $definition, PublicService $attribute, \ReflectionClass $class_or_method): void {
        $definition->setPublic(true);
      },
    );

    // Register packages in the module itself.
    parent::doRegister($container);
  }

  /**
   * {@inheritdoc}
   */
  protected function registerForCurrentModule(ServicesConfigurator $services, NamespaceDirectory $namespaceDir): void {
    $services->alias(ContainerInterface::class, 'service_container')
      ->public();

    // The logger bind has to be done per module, to avoid the error from unused
    // bind.
    $services->set('logger.channel.ock')
      ->parent('logger.channel_base')
      ->arg(0, 'ock');

    $services->defaults()
      ->autowire()
      ->autoconfigure()
      ->bind(LoggerInterface::class, new Reference('logger.channel.ock'));

    parent::registerForCurrentModule($services, $namespaceDir);
  }

  /**
   * Loads services from a services.php in a package directory.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
   *   Container builder.
   * @param string $dir
   *   Directory where the services.php is found.
   * @param string $file
   *   File name.
   *
   * @throws \Exception
   *   Bad arguments, or a file is not found.
   */
  protected static function loadPackageServicesPhp(ContainerBuilder $container, string $dir, string $file = 'services.php'): void {
    // The Drupal container builder makes all aliases public, thus prevengin
    // the removal of unused services.
    // Use a symfony ContainerBuilder instead.
    $locator = new FileLocator($dir);
    $loader = new PhpFileLoader($container, $locator);
    $loader->load($file);
  }

}
