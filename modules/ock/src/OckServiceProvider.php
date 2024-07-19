<?php

declare(strict_types = 1);

namespace Drupal\ock;

use Drupal\ock\Attribute\DI\PublicService;
use Ock\Adaptism\AdaptismPackage;
use Ock\DependencyInjection\Provider\CommonServiceProvider;
use Ock\Egg\EggNamespace;
use Ock\Ock\OckPackage;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Service provider for the Ock module.
 */
class OckServiceProvider extends ModuleServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function doRegister(ContainerBuilder $container): void {
    // Register services in the composer packages.
    static::loadPackageServicesPhp($container, dirname(EggNamespace::DIR));
    (new CommonServiceProvider())->register($container);
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
